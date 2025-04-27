<?php

namespace App\Http\Requests\Ats;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Tenant;

class JobApplicationCreateRequest extends FormRequest
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
            // job_applicants table
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:job_applicants,email'],

            // job_selection_options table
            'job_id' => 'required|integer|exists:job_posts,id',
        ];
    }
}
