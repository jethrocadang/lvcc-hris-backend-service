<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TrainingCoursesResource extends JsonResource
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
            'authorId' => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
            ],
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'thumbnailUrl' => $this->thumbnail_url
                            ? asset(Storage::url($this->thumbnail_url))
                            : null,
            'certificateUrl' => $this->certificate_url
                              ? asset (Storage::url($this->certificate_url))
                              : null,
            'maxParticipants' => $this->max_participants,
            'currentParticipants' => $this->current_participants,
            'enrollmentDeadline' => $this->enrollment_deadline,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,

        ];
    }
}
