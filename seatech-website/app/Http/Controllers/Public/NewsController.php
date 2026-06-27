<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;

class NewsController extends Controller
{
    public function index()
    {
        $posts = NewsPost::where('is_published', true)
            ->latest()
            ->paginate(9);

        return view('public.news', compact('posts'));
    }

    public function show(NewsPost $article)
    {
        abort_unless($article->is_published, 404);

        return view('public.news-show', ['post' => $article]);
    }
}
