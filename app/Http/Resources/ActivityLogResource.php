<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'event' => $this->event,
            'subjectType' => $this->subject_type,
            'subjectId' => $this->subject_id,
            'causer' => [
                'id' => $this->causer?->id,
                'name' => $this->causer?->name,
                'email' => $this->causer?->email,
            ],
            'action' => $this->parseDescription($this->description),
            'createdAt' => $this->created_at,
        ];
    }

    protected function parseDescription($description)
    {
        if (str_contains($description, '{') && str_contains($description, '}')) {
            preg_match('/^(.*?)\s*:\s*(\{.*\})$/', $description, $matches);

            if (count($matches) === 3) {
                $message = $matches[1];
                $json = json_decode($matches[2], true);

                return [
                    'message' => $message,
                    'changes' => $json ?: $matches[2], // fallback if json_decode fails
                ];
            }
        }

        return $description;
    }
}


