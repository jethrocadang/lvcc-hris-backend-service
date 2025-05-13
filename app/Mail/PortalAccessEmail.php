<?php

namespace App\Mail;

use App\Models\JobApplicant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PortalAccessEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $portalToken;
    public JobApplicant $jobApplicant;


    public function __construct(JobApplicant $jobApplicant, $portalToken)
    {
        $this->portalToken = $portalToken;
        $this->jobApplicant = $jobApplicant;
    }
    public function build()
    {
        $portalAccessUrl = config('app.frontend_url') . '/applicant-portal?token=' . $this->portalToken;

        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Email Verification')
            ->view('mail.verify-email')
            ->with([
                'portalToken' => $this->portalToken,
                'applicant' => $this->jobApplicant,
                'portalAccessUrl' => $portalAccessUrl,
            ]);
    }
}
