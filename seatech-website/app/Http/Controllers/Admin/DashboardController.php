<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Inquiry;
use App\Models\SiteSetting;
use App\Models\Student;
use App\Models\TrainingSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isInstructor = $user && method_exists($user, 'hasRole') && $user->hasRole('Instructor')
            && ! $user->hasAnyRole(['Super Admin', 'Registrar', 'Training Coordinator']);

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

        $dashboardSettings = $this->buildDashboardSettings();

        $mySchedules = collect();
        if ($isInstructor) {
            $mySchedules = TrainingSchedule::with('course')
                ->where('instructor_id', $user->id)
                ->whereIn('status', ['upcoming', 'ongoing'])
                ->orderBy('start_date')
                ->take(5)
                ->get();
        }

        return view('admin.dashboard', compact(
            'stats', 'recent_enrollments', 'recent_inquiries', 'chart',
            'mySchedules', 'isInstructor', 'dashboardSettings',
        ));
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

    private function buildDashboardSettings(): array
    {
        $settings = SiteSetting::getAllCached();

        $val = fn (string $key, ?string $fallback = '—') => $settings[$key] ?? $fallback;
        $intWithPlus = fn (string $key) => ($v = $settings[$key] ?? null) === null ? '—' : $v.'+';

        return [
            'company' => [
                ['label' => 'Full name',  'value' => $val('name')],
                ['label' => 'Short name', 'value' => $val('short_name')],
                ['label' => 'Tagline',    'value' => $val('tagline')],
            ],
            'contact' => [
                ['label' => 'Phone', 'value' => $val('contact.phone'), 'href' => $settings['contact.phone_raw'] ?? null, 'href_type' => 'tel'],
                ['label' => 'Email', 'value' => $val('contact.email'), 'href' => $settings['contact.email'] ?? null, 'href_type' => 'mailto'],
            ],
            'hours' => [
                ['label' => 'Mon–Fri',  'value' => $val('hours.weekdays')],
                ['label' => 'Saturday', 'value' => $val('hours.saturday')],
                ['label' => 'Sunday',   'value' => $val('hours.sunday')],
            ],
            'social' => [
                ['label' => 'Facebook',  'value' => $settings['social.facebook'] ?? null, 'icon' => 'facebook'],
                ['label' => 'Instagram', 'value' => $settings['social.instagram'] ?? null, 'icon' => 'instagram'],
                ['label' => 'YouTube',   'value' => $settings['social.youtube'] ?? null, 'icon' => 'youtube'],
                ['label' => 'LinkedIn',  'value' => $settings['social.linkedin'] ?? null, 'icon' => 'linkedin'],
            ],
            'stats' => [
                ['label' => 'Years of service',     'value' => $intWithPlus('stats.years_excellence')],
                ['label' => 'Graduates',           'value' => $intWithPlus('stats.graduates')],
                ['label' => 'Certified instructors', 'value' => $intWithPlus('stats.certified_instructors')],
            ],
        ];
    }
}
