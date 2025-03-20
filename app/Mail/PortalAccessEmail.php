<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PortalAccessEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $portalToken;

    public function __construct(string $portalToken)
    {
        $this->portalToken = $portalToken;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Your Portal Access Token')
            ->view('mail.portal-email')
            ->with([
                'portalToken' => $this->portalToken,
            ]);
    }
}
