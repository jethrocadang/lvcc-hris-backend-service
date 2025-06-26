<?php

namespace App\Http\Resources\Eth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;


class ExternalTrainingAttendanceResource extends JsonResource
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
            'employeeId' => [
                'id' => $this->employee?->id,
                'name' => $this->employee?->name
            ],
            'trainingType' => $this->training_type,
            'title' => $this->title,
            'description' => $this->description,
            'provider' => $this->provider,
            'trainingMode' => $this->training_mode,
            'location' => $this->location,
            'certificateUrl' => $this->certificate_url
                            ? asset(Storage::url($this->certificate_url))
                            : null,
            'hoursCompleted' => $this->hours_completed,
            'dateStarted' => $this->date_started,
            'dateCompleted' => $this->date_completed,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
