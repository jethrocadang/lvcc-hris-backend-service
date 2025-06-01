<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TrainingCourseModuleResource extends JsonResource
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
            'courseId' => [
                'id' => $this->course?->id,
                'title' => $this->course?->title
            ],
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'certificateUrl' => $this->certificate_url
                            ? asset(Storage::url($this->certificate_url))
                            : null,
            'videoUrl' => $this->video_url,
            'thumbnailUrl' => $this->thumbnail_url
                            ? asset(Storage::url($this->thumbnail_url))
                            : null,
            'sequenceOrder' => $this->sequence_order,
            'fileContent' => $this->file_content
                            ? asset(Storage::url($this->file_content))
                            : null,
            'textContent' => $this->text_content,
            'imageContent' => $this->image_content
                            ? asset(Storage::url($this->image_content))
                            : null,
            'expirationDate' => $this->expiration_date,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
