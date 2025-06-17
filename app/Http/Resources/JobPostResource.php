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
            'department' => $this->when($this->relationLoaded('department'), function () {
                return [
                    'id' => $this->department->id,
                    'name' => $this->department->name,
                    // 'description' => $this->department->description,
                ];
            }),
            'departmentId' => $this->department_id,
            'workType' => $this->work_type,
            'jobType' => $this->job_type,
            'title' => $this->title,
            'description' => $this->description,
            'iconUrl' => $this->icon_url,
            'status' => $this->status,
            'location' => $this->location,
            'category' => $this->category,
            'updatedAt' => $this->updated_at,
            'createdAt' => $this->created_at
        ];
    }
}
