<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use App\Models\Inquiry;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => Student::count(),
            'enrollments' => Enrollment::count(),
            'courses' => Course::where('is_active', true)->count(),
            'inquiries' => Inquiry::where('is_read', false)->count(),
            'pending_enrollments' => Enrollment::where('status', 'pending')->count(),
            'upcoming_schedules' => \App\Models\TrainingSchedule::where('status', 'upcoming')->count(),
        ];

        $recent_enrollments = Enrollment::with(['student', 'trainingSchedule.course'])
            ->latest()
            ->take(5)
            ->get();

        $recent_inquiries = Inquiry::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_enrollments', 'recent_inquiries'));
    }
}
