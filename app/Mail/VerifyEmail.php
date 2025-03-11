<?php

namespace App\Mail;

use App\Models\JobApplicant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant;

    public function __construct(JobApplicant $applicant)
    {
        $this->applicant = $applicant;
    }

    public function build()
    {
        return $this->subject('Verify Your Email')
            ->view('emails.verify-email')
            ->with([
                'verificationUrl' => url('/api/verify-email/' . $this->applicant->verification_token),
            ]);
    }
}
