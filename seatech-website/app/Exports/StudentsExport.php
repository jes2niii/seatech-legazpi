<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Student::query();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Last Name',
            'First Name',
            'Middle Name',
            'Email',
            'Mobile',
            'Gender',
            'Date of Birth',
            'Seaman Book #',
            'Address',
            'Created At',
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->last_name,
            $student->first_name,
            $student->middle_name,
            $student->email,
            $student->mobile_number,
            $student->gender ? ucfirst($student->gender) : '',
            optional($student->date_of_birth)->format('Y-m-d'),
            $student->seaman_book_number,
            $student->address,
            $student->created_at->format('Y-m-d H:i'),
        ];
    }
}
