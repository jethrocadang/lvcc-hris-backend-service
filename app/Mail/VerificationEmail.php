<?php

namespace App\Mail;

use App\Models\JobApplicant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\NewTenantModel;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public JobApplicant $applicant;

    public int $jobId;


    public function __construct(JobApplicant $applicant, int $jobId)
    {
        $this->applicant = $applicant;
        $this->jobId = $jobId;
    }

    public function build()
    {
        $verifyEmailUrl = config('app.frontend_url') . '/verify-email?token='
        . $this->applicant->verification_token
        . '&job_id=' . $this->jobId;

        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('[Verify Email] - Verify your email now')
            ->view('mail.verify-email')
            ->with([
                'applicant' => $this->applicant,
                'verifyEmailUrl' => $verifyEmailUrl,
            ]);
    }
}
