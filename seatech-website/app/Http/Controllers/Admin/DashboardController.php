<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Inquiry;
use App\Models\Student;
use App\Models\TrainingSchedule;
use Carbon\Carbon;
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
            'upcoming_schedules' => TrainingSchedule::where('status', 'upcoming')->count(),
            'certificates' => Certificate::count(),
        ];

        $recent_enrollments = Enrollment::with(['student', 'trainingSchedule.course'])
            ->latest()
            ->take(5)
            ->get();

        $recent_inquiries = Inquiry::latest()
            ->take(5)
            ->get();

        $chart = $this->buildChartData();

        return view('admin.dashboard', compact('stats', 'recent_enrollments', 'recent_inquiries', 'chart'));
    }

    private function buildChartData(): array
    {
        $months = [];
        $enrollmentCounts = [];
        $studentCounts = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $enrollmentCounts[] = Enrollment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $studentCounts[] = Student::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        $enrollmentsByStatus = Enrollment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $topCourses = Course::withCount(['trainingSchedules as enrollment_count' => function ($q) {
                $q->join('enrollments', 'enrollments.training_schedule_id', '=', 'training_schedules.id');
            }])
            ->orderByDesc('enrollment_count')
            ->limit(5)
            ->get(['id', 'code', 'title'])
            ->map(fn ($c) => [
                'label' => $c->code,
                'value' => (int) $c->enrollment_count,
            ])
            ->all();

        return [
            'months' => $months,
            'enrollments_by_month' => $enrollmentCounts,
            'students_by_month' => $studentCounts,
            'enrollments_by_status' => [
                'labels' => array_keys($enrollmentsByStatus),
                'values' => array_values($enrollmentsByStatus),
            ],
            'top_courses' => $topCourses,
        ];
    }
}
