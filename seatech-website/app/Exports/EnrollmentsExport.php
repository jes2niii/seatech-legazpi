<?php

namespace App\Exports;

use App\Models\Enrollment;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EnrollmentsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Enrollment::with(['student', 'trainingSchedule.course']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Email',
            'Mobile',
            'Course',
            'Schedule',
            'Status',
            'Payment Status',
            'Enrolled At',
        ];
    }

    public function map($enrollment): array
    {
        return [
            $enrollment->id,
            $enrollment->student->full_name ?? trim(($enrollment->student->first_name ?? '') . ' ' . ($enrollment->student->last_name ?? '')),
            $enrollment->student->email ?? '',
            $enrollment->student->mobile_number ?? '',
            $enrollment->trainingSchedule->course->title ?? 'N/A',
            optional($enrollment->trainingSchedule->start_date)->format('Y-m-d') . ' to ' . optional($enrollment->trainingSchedule->end_date)->format('Y-m-d'),
            ucfirst($enrollment->status),
            ucfirst(str_replace('_', ' ', $enrollment->payment_status)),
            $enrollment->created_at->format('Y-m-d H:i'),
        ];
    }
}
