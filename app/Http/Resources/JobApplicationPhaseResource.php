<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationPhaseResource extends JsonResource
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
            'acceptanceEmailTemplateId' => $this->acceptance_email_template_id,
            'rejectionEmailTemplateId' => $this->rejection_email_template_id,
            'title' => $this->title,
            'description' => $this->description,
            'sequenceOrder' => $this->sequence_order
        ];
    }
}
