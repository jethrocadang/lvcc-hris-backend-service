<?php

namespace App\Http\Requests\Ats;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Tenant;

class UpdateJobApplicationPhaseTwoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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
            'current_phase_id' => 'required|exists:job_application_phases,id',
            'next_phase_id' => 'nullable|exists:job_application_phases,id',
            'job_application_id' => 'required|exists:job_applications,id',
            'reviewer_remarks' => 'nullable|string',
            'status' => 'nullable|in:accepted,rejected',
            'screening_type' => 'nullable|string'
        ];
    }
}
