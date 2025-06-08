<?php

namespace App\Mail;

use App\Models\AtsEmailTemplate;
use App\Models\JobApplicant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobApplicationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $portalToken;
    public JobApplicant $jobApplicant;
    public AtsEmailTemplate $email;


    public function __construct(JobApplicant $jobApplicant, AtsEmailTemplate $email, $portalToken)
    {
        $this->jobApplicant = $jobApplicant;
        $this->email = $email;
        $this->portalToken = $portalToken;
    }
    public function build()
    {
        $portalAccessUrl = config('app.frontend_url') . '/applicant-portal/entry?token=' . $this->portalToken;

        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->email->subject)
            ->view('mail.portal-email')
            ->with([
                'portalToken' => $this->portalToken,
                'applicant' => $this->jobApplicant,
                'email' => $this->email,
                'portalAccessUrl' => $portalAccessUrl,
            ]);
    }
}

