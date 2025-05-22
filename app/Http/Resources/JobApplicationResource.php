<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'jobApplicantId' => $this->job_applicant_id,
            'portalToken' => $this->portal_token,
            'createdAt'        => $this->created_at,
        ];
    }
}
