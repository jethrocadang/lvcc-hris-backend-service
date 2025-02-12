<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'work_type' => $this->work_type,
            'job_type' => $this->job_type,
            'title' => $this->title,
            'description' => $this->description,
            'icon_url' => $this->icon_url,
            'status' => $this->status,
            'location' => $this->location,
            'schedule' => $this->schedule,
            'updated_at' => $this->updated_at->toDateTimeString(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
