<?php

namespace App\Exports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CertificatesExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Certificate::with(['student', 'course']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Certificate Number',
            'Student Name',
            'Course',
            'Issued Date',
            'Verified',
            'Created At',
        ];
    }

    public function map($cert): array
    {
        return [
            $cert->id,
            $cert->certificate_number,
            $cert->student->full_name ?? trim(($cert->student->first_name ?? '') . ' ' . ($cert->student->last_name ?? '')),
            $cert->course->title ?? 'N/A',
            $cert->issued_date->format('Y-m-d'),
            $cert->is_verified ? 'Yes' : 'No',
            $cert->created_at->format('Y-m-d H:i'),
        ];
    }
}
