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
            'template_type' => $this->template_type,
            'email_title' => $this->email_title,
            'email_body' => $this->email_body,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
