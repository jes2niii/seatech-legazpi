<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnrollmentRejected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enrollment $enrollment, public ?string $reason = null) {}

    public function build()
    {
        $site = config('site');
        $studentName = $this->enrollment->student->full_name ?? 'Trainee';
        $courseTitle = $this->enrollment->trainingSchedule->course->title ?? 'your course';

        $reasonBlock = $this->reason
            ? "<p><strong>Reason:</strong> {$this->reason}</p>"
            : '';

        return $this->subject("Enrollment Update - {$courseTitle}")
            ->html("
                <h2>Hello {$studentName},</h2>
                <p>We regret to inform you that your enrollment at <strong>{$site['name']}</strong> for the following course could not be approved at this time:</p>
                <p><strong>Course:</strong> {$courseTitle}<br>
                <strong>Reference #:</strong> {$this->enrollment->id}</p>
                {$reasonBlock}
                <p>If you have any questions or would like to discuss alternative options, please contact us at {$site['contact']['email']} or call {$site['contact']['phone']}.</p>
                <p>— {$site['short_name']}</p>
            ");
    }
}
