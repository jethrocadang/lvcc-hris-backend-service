<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationProgressResource extends JsonResource
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
            'jobApplicationId' => $this->job_appllication_id,
            'joApplicationPhaseId' => $this->job_application_phase_id,
            'reviewedBy' => $this->reviewed_by,
            'reviewerRemarks' => $this->reviewer_remarks,
            'status' => $this->status,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date
        ];
    }
}
