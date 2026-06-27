<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\Searchable;
use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    use Searchable;

    public function __construct()
    {
        $this->middleware('permission:manage news');
    }

    public function index(Request $request)
    {
        $query = NewsPost::with('author');
        $query = $this->applySearch($query, $request, ['title', 'content']);
        $news = $query->latest()->paginate(10)->withQueryString();

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'is_published' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['author_id'] = auth()->id();
        $data['is_published'] = $request->boolean('is_published');

        if ($data['is_published']) {
            $data['published_at'] = now();
        }

        $news = NewsPost::create($data);

        if ($request->hasFile('featured_image')) {
            $news->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        return redirect()->route('admin.news.index')->with('success', 'News post created successfully.');
    }

    public function edit(NewsPost $newsPost)
    {
        $news = $this->resolveNews($newsPost);

        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, NewsPost $newsPost)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'is_published' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        $news = $this->resolveNews($newsPost);
        if ($data['is_published'] && ! $news->published_at) {
            $data['published_at'] = now();
        }

        $news->update($data);

        if ($request->hasFile('featured_image')) {
            $news->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        return redirect()->route('admin.news.index')->with('success', 'News post updated successfully.');
    }

    public function destroy(NewsPost $newsPost)
    {
        $newsPost = $this->resolveNews($newsPost);
        $newsPost->clearMediaCollection('featured_image');
        $newsPost->delete();

        return redirect()->route('admin.news.index')->with('success', 'News post deleted successfully.');
    }

    /**
     * Resolve a NewsPost from the route binding. Falls back to looking up
     * the model by the {news} route parameter so we always work with a
     * fully-loaded instance (the implicit binding is unreliable here).
     */
    private function resolveNews(NewsPost $bound): NewsPost
    {
        $id = request()->route('news');

        if ($id && $bound->getKey() === (int) $id && $bound->exists) {
            return $bound;
        }

        $resolved = $id ? NewsPost::find($id) : null;

        return $resolved ?? $bound;
    }
}
