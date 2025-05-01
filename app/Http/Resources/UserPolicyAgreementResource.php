<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPolicyAgreementResource extends JsonResource
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
            'policyAcceptedAt' => $this->policy_accepted_at,
            'policy' => new PolicyResource($this->whenLoaded('policy')),
            'userAgreement' => new UserAgreementResource($this->whenLoaded('user_agreement')),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
