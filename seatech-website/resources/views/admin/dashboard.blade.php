@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@if(!empty($isInstructor) && $isInstructor)
    <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-[#D4A017]">
        <h2 class="text-lg font-semibold text-[#003366] mb-4">My Upcoming Schedules</h2>
        @if(isset($mySchedules) && $mySchedules->count())
            <ul class="divide-y divide-gray-200">
                @foreach($mySchedules as $schedule)
                    <li class="py-3 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $schedule->course->code ?? '' }} — {{ $schedule->course->title ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $schedule->start_date->format('M d, Y') }} – {{ $schedule->end_date->format('M d, Y') }} · {{ $schedule->venue ?: 'Venue TBA' }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($schedule->status == 'upcoming') bg-blue-100 text-blue-800
                            @elseif($schedule->status == 'ongoing') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($schedule->status) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-sm">You have no upcoming schedules assigned to you yet.</p>
        @endif
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-[#003366]">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Students</h3>
        <p class="text-2xl font-bold text-[#003366] mt-1">{{ $stats['students'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-[#0077B6]">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Enrollments</h3>
        <p class="text-2xl font-bold text-[#0077B6] mt-1">{{ $stats['enrollments'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-[#D4A017]">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Active Courses</h3>
        <p class="text-2xl font-bold text-[#D4A017] mt-1">{{ $stats['courses'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-yellow-500">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Pending Enroll.</h3>
        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending_enrollments'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-green-500">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Upcoming Sched.</h3>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['upcoming_schedules'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-purple-500">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Certificates</h3>
        <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['certificates'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-red-500">
        <h3 class="text-xs font-medium text-gray-500 uppercase">Unread Inq.</h3>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['inquiries'] ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Enrollments & Students (Last 12 Months)</h2>
        <div class="h-64">
            <canvas id="enrollmentTrendChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Enrollments by Status</h2>
        <div class="h-64">
            <canvas id="enrollmentStatusChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Top Courses by Enrollment</h2>
        <div class="h-64">
            <canvas id="topCoursesChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Inquiries</h2>
        @if(isset($recent_inquiries) && $recent_inquiries->count())
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($recent_inquiries as $inquiry)
                <tr>
                    <td class="px-4 py-2 text-sm">{{ $inquiry->name }}</td>
                    <td class="px-4 py-2 text-sm">{{ $inquiry->subject }}</td>
                    <td class="px-4 py-2 text-sm">{{ $inquiry->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-gray-500 text-sm">No recent inquiries.</p>
        @endif
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Enrollments</h2>
    @if(isset($recent_enrollments) && $recent_enrollments->count())
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($recent_enrollments as $enrollment)
            <tr>
                <td class="px-4 py-2 text-sm">{{ $enrollment->student->full_name ?? ($enrollment->student->first_name . ' ' . $enrollment->student->last_name) }}</td>
                <td class="px-4 py-2 text-sm">{{ $enrollment->trainingSchedule->course->title ?? 'N/A' }}</td>
                <td class="px-4 py-2">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($enrollment->status == 'approved') bg-green-100 text-green-800
                        @elseif($enrollment->status == 'rejected') bg-red-100 text-red-800
                        @elseif($enrollment->status == 'completed') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="text-gray-500 text-sm">No recent enrollments.</p>
    @endif
</div>
@endsection

@push('scripts')
@if(isset($chart))
<script>
    const brandColors = {
        navy: '#003366',
        ocean: '#0077B6',
        gold: '#D4A017',
        success: '#16a34a',
        warning: '#eab308',
        danger: '#dc2626',
    };

    window.reinitDashboardCharts = function() {
        if (typeof Chart === 'undefined') return;
        if (!document.getElementById('enrollmentTrendChart')) return;

        new Chart(document.getElementById('enrollmentTrendChart'), {
            type: 'line',
            data: {
                labels: @json($chart['months']),
                datasets: [
                    {
                        label: 'Enrollments',
                        data: @json($chart['enrollments_by_month']),
                        borderColor: brandColors.navy,
                        backgroundColor: brandColors.navy + '20',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'New Students',
                        data: @json($chart['students_by_month']),
                        borderColor: brandColors.ocean,
                        backgroundColor: brandColors.ocean + '20',
                        tension: 0.3,
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });

        new Chart(document.getElementById('enrollmentStatusChart'), {
            type: 'doughnut',
            data: {
                labels: @json($chart['enrollments_by_status']['labels']),
                datasets: [{
                    data: @json($chart['enrollments_by_status']['values']),
                    backgroundColor: [brandColors.warning, brandColors.success, brandColors.danger, brandColors.ocean, brandColors.gold],
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } },
            },
        });

        @if(count($chart['top_courses']))
        new Chart(document.getElementById('topCoursesChart'), {
            type: 'bar',
            data: {
                labels: @json(array_column($chart['top_courses'], 'label')),
                datasets: [{
                    label: 'Enrollments',
                    data: @json(array_column($chart['top_courses'], 'value')),
                    backgroundColor: brandColors.gold,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });
        @else
        const topCoursesEl = document.getElementById('topCoursesChart');
        if (topCoursesEl) topCoursesEl.parentElement.innerHTML = '<p class="text-gray-500 text-sm flex items-center justify-center h-full">No enrollment data yet.</p>';
        @endif
    };

    window.reinitDashboardCharts();
</script>
@endif
@endpush
