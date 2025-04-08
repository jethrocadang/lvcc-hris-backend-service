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
            'admin' => 'exists:users,id', 
            'scheduled_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'slot_status' => 'required|in:available,booked,cancelled',
        ];
    }
}
