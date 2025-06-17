<?php

namespace App\Http\Requests\Eth;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationFormFilterRequest extends FormRequest
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
            'filter.title' => 'nullable|string',
            'filter.employee_training_course_id' => 'nullable|integer',
            'filter.is_active' => 'nullable|boolean',
            'sort' => 'nullable|string|in:created_at,title,-created_at,-title',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
