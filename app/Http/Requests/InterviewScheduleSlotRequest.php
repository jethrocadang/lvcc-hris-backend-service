<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InterviewScheduleSlotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'scheduled_date' => ['required', 'date'],
            'timeSlots' => ['required', 'array', 'min:1'],
            'timeSlots.*.start_time' => ['sometimes', 'date_format:H:i'],
            'timeSlots.*.is_available' => ['sometimes', 'boolean']
        ];
    }
}
