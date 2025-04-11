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

    public function __construct(JobApplicant $applicant)
    {
        $this->applicant = $applicant;
    }

    public function build()
    {
        $verifyEmailUrl = 'frontend.com' . '/verify-email?token='. $this->applicant->verification_token;

        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Verify Your Email')
            ->view('mail.verify-email')
            ->with([
                'applicant' => $this->applicant,
                'verifyEmailUrl' => $verifyEmailUrl,
            ]);
    }
}
