<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Facility;
use App\Models\NewsPost;
use App\Models\Testimonial;
use App\Models\TrainingSchedule;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function home()
    {
        $courses = Course::with('category')
            ->where('is_active', true)
            ->notArchived()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('sort_order')
            ->take(5)
            ->get();

        $news = NewsPost::where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        $facilities = Facility::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('welcome', compact('courses', 'testimonials', 'news', 'facilities'));
    }

    public function index(Request $request)
    {
        $query = Course::with('category')
            ->where('is_active', true)
            ->notArchived()
            ->orderBy('sort_order');

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('q')) {
            $term = '%'.trim($request->q).'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('code', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        }

        $courses = $query->paginate(9)->withQueryString();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('public.courses', compact('courses', 'categories'));
    }

    public function show(Course $course)
    {
        abort_if($course->isArchived() || ! $course->is_active, 404);

        $course->load(['category', 'trainingSchedules.instructor']);

        return view('public.courses-show', compact('course'));
    }

    public function calendar()
    {
        $schedules = TrainingSchedule::with(['course', 'instructor'])
            ->whereIn('status', ['upcoming', 'ongoing'])
            ->orderBy('start_date')
            ->get();

        return view('public.calendar', compact('schedules'));
    }
}
