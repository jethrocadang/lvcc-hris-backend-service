<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Tenant;


class TokenRequest extends FormRequest
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
            'token' => 'required|string',
            'job_id' => 'sometimes|exists:job_posts,id'
        ];
    }
}
