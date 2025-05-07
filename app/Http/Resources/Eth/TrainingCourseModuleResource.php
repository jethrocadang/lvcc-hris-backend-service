<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'videoUrl' => $this->video_url,
            'thumbnailUrl' => $this->thumbnail_url,
            'sequenceOrder' => $this->sequence_order,
            'fileContent' => $this->file_content,
            'textContent' => $this->text_content,
            'imageContent' => $this->image_content,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
