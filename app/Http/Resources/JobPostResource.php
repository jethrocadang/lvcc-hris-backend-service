<?php

namespace App\Http\Resources;

use App\Models\Department;
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

        // Try to include department data in multiple ways
        if ($this->relationLoaded('department') && $this->department) {
            // If relationship is already loaded
            $data['department'] = [
                'id' => $this->department->id,
                'name' => $this->department->name,
            ];
        } elseif ($this->department_id) {
            // If relationship is not loaded but we have department_id, try to fetch it
            try {
                $department = Department::find($this->department_id);
                if ($department) {
                    $data['department'] = [
                        'id' => $department->id,
                        'name' => $department->name,
                    ];
                }
            } catch (\Exception $e) {
                // Silently fail if department can't be loaded
            }
        }

        return $data;
    }
}
