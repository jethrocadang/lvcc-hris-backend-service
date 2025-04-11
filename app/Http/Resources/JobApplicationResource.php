<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'email' => $this->email,
            'verificationToken' => $this->verification_token,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}

