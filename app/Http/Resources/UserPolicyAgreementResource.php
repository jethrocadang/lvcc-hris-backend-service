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
            'policy_accepted_at' => $this->policy_accepted_at,
            'policy' => new PolicyResource($this->whenLoaded('policy')),
            'user_agreement' => new UserAgreementResource($this->whenLoaded('user_agreement')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
