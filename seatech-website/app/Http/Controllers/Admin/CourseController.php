<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\Searchable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use Searchable;

    public function __construct()
    {
        $this->middleware('permission:manage courses');
    }

    public function index(Request $request)
    {
        $query = Course::with('category');
        $query = $this->applySearch($query, $request, ['title', 'code', 'description']);

        $filter = $request->get('filter', 'active');
        if ($filter === 'archived') {
            $query->archived();
        } elseif ($filter === 'all') {
            // no scope
        } else {
            $query->notArchived();
        }

        $courses = $query->latest()->paginate(10)->withQueryString();

        return view('admin.courses.index', compact('courses', 'filter'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:50|unique:courses,code',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|max:100',
            'fee' => 'required|numeric|min:0',
            'prerequisites' => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        $course = Course::create($data);

        if ($request->hasFile('featured_image')) {
            $course->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load(['category', 'trainingSchedules']);

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|string|max:50|unique:courses,code,'.$course->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|max:100',
            'fee' => 'required|numeric|min:0',
            'prerequisites' => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        $course->update($data);

        if ($request->hasFile('featured_image')) {
            $course->addMediaFromRequest('featured_image')->toMediaCollection('featured_image');
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->clearMediaCollection('featured_image');
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }

    public function archive(Course $course)
    {
        $course->update(['archived_at' => now()]);

        return redirect()->route('admin.courses.index', ['filter' => 'archived'])
            ->with('success', 'Course archived successfully.');
    }

    public function restore(Course $course)
    {
        $course->update(['archived_at' => null]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course restored successfully.');
    }
}
