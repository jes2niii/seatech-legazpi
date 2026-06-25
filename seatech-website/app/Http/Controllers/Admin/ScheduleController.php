<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingSchedule;
use App\Models\Course;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage schedules');
    }

    public function index()
    {
        $schedules = TrainingSchedule::with('course')->latest()->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $courses = Course::where('is_active', true)->orderBy('title')->get();
        return view('admin.schedules.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        TrainingSchedule::create($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function show(TrainingSchedule $schedule)
    {
        $schedule->load('course');
        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(TrainingSchedule $schedule)
    {
        $courses = Course::where('is_active', true)->orderBy('title')->get();
        return view('admin.schedules.edit', compact('schedule', 'courses'));
    }

    public function update(Request $request, TrainingSchedule $schedule)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $schedule->update($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(TrainingSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
