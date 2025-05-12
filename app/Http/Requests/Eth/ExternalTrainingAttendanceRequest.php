<?php

namespace App\Http\Requests\Eth;

use Illuminate\Foundation\Http\FormRequest;

class ExternalTrainingAttendanceRequest extends FormRequest
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
            'employee_id' => 'nullable|exists:employees,id',
            'training_type' => 'required|in:compliance,external,other',
            'certificate_url' => 'required|url|max:255',
            'date_started' => 'required|date',
            'date_completed' => 'required|date',
        ];
    }
}
