<?php

namespace App\Http\Requests\Eth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Tenant;

class EvaluationResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Determine the tenant first before running the request.
     * @return void
     */
    protected function prepareForValidation()
    {
        // This checks if the requests comes from a tenant, then sets the database to select the tenant database.
        if (Tenant::class) {
            DB::setDefaultConnection('tenant');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'evaluation_form_id' => 'required|exists:evaluation_forms,id',
            'training_course_id' => 'required|exists:employee_training_courses,id',
            'responses' => 'required|array|min:1',
            'responses.*.evaluation_item_id' => 'required|exists:evaluation_items,id',
            'responses.*.score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
