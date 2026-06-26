<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enrollment $enrollment) {}

    public function build()
    {
        $site = config('site');
        $studentName = $this->enrollment->student->full_name ?? 'Trainee';
        $courseTitle = $this->enrollment->trainingSchedule->course->title ?? 'your course';
        $startDate = optional($this->enrollment->trainingSchedule->start_date)->format('F d, Y');

        return $this->subject("Enrollment Approved - {$courseTitle}")
            ->html("
                <h2>Congratulations, {$studentName}!</h2>
                <p>Your enrollment at <strong>{$site['name']}</strong> has been <strong>approved</strong>.</p>
                <p><strong>Course:</strong> {$courseTitle}<br>
                <strong>Schedule:</strong> {$startDate}<br>
                <strong>Reference #:</strong> {$this->enrollment->id}</p>
                <p>Please settle your payment and complete the required documents before the training starts. You may:</p>
                <ul>
                    <li>Visit our office at {$site['address']['city']}, {$site['address']['province']}</li>
                    <li>Call us at {$site['contact']['phone']}</li>
                    <li>Email us at {$site['contact']['email']}</li>
                </ul>
                <p>Office hours: {$site['office_hours']['weekdays']} (Mon-Fri)</p>
                <p>— {$site['short_name']}</p>
            ");
    }
}
