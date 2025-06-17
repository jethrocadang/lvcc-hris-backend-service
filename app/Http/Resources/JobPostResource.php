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
        $data = [
            'id' => $this->id,
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

        // Only include department data if the relationship is loaded and accessible
        if ($this->relationLoaded('department') && $this->department) {
            $data['department'] = [
                'id' => $this->department->id,
                'name' => $this->department->name,
            ];
        }

        return $data;
    }
}
