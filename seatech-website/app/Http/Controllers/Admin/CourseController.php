<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage courses');
    }

    public function index()
    {
        $courses = Course::with('category')->latest()->paginate(10);
        return view('admin.courses.index', compact('courses'));
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
            'max_participants' => 'required|integer|min:1',
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
            'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string|max:100',
            'fee' => 'required|numeric|min:0',
            'prerequisites' => 'nullable|string',
            'max_participants' => 'required|integer|min:1',
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
}
