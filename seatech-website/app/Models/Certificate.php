<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Certificate extends Model
{
    use LogsActivity;

    protected $fillable = [
        'enrollment_id', 'student_id', 'course_id', 'certificate_number',
        'issued_date', 'qr_code', 'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'issued_date' => 'date',
            'is_verified' => 'boolean',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['certificate_number', 'issued_date', 'is_verified'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('certificate');
    }
}
