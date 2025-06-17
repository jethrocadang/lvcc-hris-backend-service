<?php

namespace App\Http\Requests\Eth;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationFormRequest extends FormRequest
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
            'employee_training_course_id' => 'required|exists:tenant.employee_training_courses,id',
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',

            // Categories are optional for initial creation but follow a specific format
            'categories' => 'nullable|array',
            'categories.*.id' => 'nullable|integer|exists:tenant.evaluation_categories,id',
            'categories.*.title' => 'required|string|max:255',

            // Items within categories
            'categories.*.items' => 'nullable|array',
            'categories.*.items.*.id' => 'nullable|integer|exists:evaluation_items,id',
            'categories.*.items.*.question' => 'required|string',
        ];
    }
}
