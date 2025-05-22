<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobSelectionOptionsResource extends JsonResource
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
            'jobId' => $this->job_id,
            'jobApplicationId' => $this->job_application_id,
            'priority' => $this->priority,
            'status' => $this->status,
            'jobPost' => new JobPostResource($this->whenLoaded('jobPost')),
        ];
    }
}
