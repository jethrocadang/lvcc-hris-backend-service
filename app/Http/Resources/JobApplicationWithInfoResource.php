<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationWithInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'portalToken' => $this->portal_token,
            'createdAt'        => $this->created_at,
            'jobApplicant' => new JobApplicantResource($this->whenLoaded('jobApplicant')),
            'jobSelectionOptions' => JobSelectionOptionsResource::collection($this->whenLoaded(('jobSelectionOptions'))),
            'jobApplicationProgress' =>  JobApplicationProgressResource::collection(($this->whenLoaded('jobApplicationProgress'))),
            'jobInterviewScheduling' => JobInterviewSchedulingResource::collection($this->whenLoaded('jobInterviewScheduling'))
        ];
    }
}
