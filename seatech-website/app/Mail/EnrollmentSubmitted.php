<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enrollment $enrollment) {}

    public function build()
    {
        $site = config('site');
        $studentName = $this->enrollment->student->full_name ?? 'Trainee';
        $courseTitle = $this->enrollment->trainingSchedule->course->title ?? 'your course';
        $startDate = optional($this->enrollment->trainingSchedule->start_date)->format('F d, Y');

        $emergencyContact = $this->enrollment->emergency_contact_name
            ? "<li><strong>Emergency Contact:</strong> {$this->enrollment->emergency_contact_name} ({$this->enrollment->emergency_contact_relationship}) — {$this->enrollment->emergency_contact_mobile}</li>"
            : '';

        return $this->subject("Enrollment Received - {$courseTitle}")
            ->html("
                <h2>Hello {$studentName},</h2>
                <p>Thank you for enrolling at <strong>{$site['name']}</strong>.</p>
                <p>We have received your application for the following training:</p>
                <ul>
                    <li><strong>Course:</strong> {$courseTitle}</li>
                    <li><strong>Schedule:</strong> {$startDate}</li>
                    <li><strong>Reference #:</strong> {$this->enrollment->id}</li>
                    {$emergencyContact}
                </ul>
                <p>Our team will review your application and contact you at <strong>{$this->enrollment->student->email}</strong> within 1-2 business days.</p>
                <p>You may also visit our office to complete payment and submit original documents.</p>
                <p>— {$site['short_name']}</p>
            ");
    }
}
