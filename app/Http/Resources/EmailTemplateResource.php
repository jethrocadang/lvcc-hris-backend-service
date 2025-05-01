<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemplateResource extends JsonResource
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
            'version' => $this->version,
            'templateType' => $this->template_type,
            'emailTitle' => $this->email_title,
            'emailBody' => $this->email_body,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
