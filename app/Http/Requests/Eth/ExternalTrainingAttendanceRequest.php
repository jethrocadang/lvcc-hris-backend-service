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
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        $rules = [
            'employee_id' => 'nullable|exists:employees,id',
            'training_type' => 'required|in:compliance,external,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'provider' => 'required|string|max:255',
            'training_mode' => 'required|string|in:online,in-person,hybrid',
            'location' => 'required|string|max:255',
            'hours_completed' => 'required|integer|min:1',
            'date_started' => 'required|date',
            'date_completed' => 'required|date|after_or_equal:date_started',
        ];

        // Make certificate_url required only for new records
        if ($isUpdate) {
            $rules['certificate_url'] = 'nullable|file|image|mimes:jpeg,jpg,png|max:2048';
        } else {
            $rules['certificate_url'] = 'required|file|image|mimes:jpeg,jpg,png|max:2048';
        }

        return $rules;
    }
}
