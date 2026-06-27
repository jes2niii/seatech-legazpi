<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\Searchable;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\TrainingSchedule;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    use Searchable;

    public function __construct()
    {
        $this->middleware('permission:manage schedules');
    }

    public function index(Request $request)
    {
        $query = TrainingSchedule::with(['course', 'instructor']);
        $query = $this->applySearch($query, $request, ['venue'], [
            'course' => ['title', 'code'],
            'instructor' => ['name', 'email'],
        ]);
        $schedules = $query->latest()->paginate(10)->withQueryString();

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $courses = Course::where('is_active', true)->orderBy('title')->get();
        $instructors = $this->assignableInstructors();

        return view('admin.schedules.create', compact('courses', 'instructors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'instructor_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $data['instructor_id'] = $this->validateInstructor($data['instructor_id'] ?? null);

        TrainingSchedule::create($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function show(TrainingSchedule $schedule)
    {
        $schedule->load(['course', 'instructor']);

        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(TrainingSchedule $schedule)
    {
        $courses = Course::where('is_active', true)->orderBy('title')->get();
        $instructors = $this->assignableInstructors();

        return view('admin.schedules.edit', compact('schedule', 'courses', 'instructors'));
    }

    public function update(Request $request, TrainingSchedule $schedule)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'instructor_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $data['instructor_id'] = $this->validateInstructor($data['instructor_id'] ?? null);

        $schedule->update($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(TrainingSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully.');
    }

    private function assignableInstructors()
    {
        return User::role('Instructor')->orderBy('name')->get(['id', 'name', 'email']);
    }

    private function validateInstructor(?int $instructorId): ?int
    {
        if (! $instructorId) {
            return null;
        }

        $user = User::find($instructorId);
        if (! $user || ! $user->hasRole('Instructor')) {
            return null;
        }

        return $user->id;
    }
}
