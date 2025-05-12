<?php

namespace App\Http\Requests\Eth;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeCourseProgressRequest extends FormRequest
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
            'course_id' => 'required|exists:tenant.employee_training_courses,id',
            'module_id' => 'required|exists:tenant.training_course_modules,id',
            'status' => 'required|in:not-started,in-progress,completed',
            'watched_seconds' => 'nullable|integer|min:0',
            'last_position' => 'nullable|integer|min:0',
            'completion_date' => 'nullable|date',
        ];
    }
}
