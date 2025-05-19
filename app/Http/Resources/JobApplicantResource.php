<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'avatar' => $this->avatar_url,
            'emailVerifiedAt' => $this->email_verified_at,
            'verificationToken' => $this->verification_token,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'jobApplication' => new JobApplicationResource($this->whenLoaded('jobApplication')),
            'jobApplicantInformation' => new JobApplicantInformationResource($this->whenLoaded('jobApplicantInformation'))

        ];
    }
}
