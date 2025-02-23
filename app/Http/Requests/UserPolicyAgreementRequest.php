<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPolicyAgreementRequest extends FormRequest
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
            'policy_id' => 'required|integer',
            'user_agreement_id' => 'required|integer',
            'policy_accepted_at' => 'nullable|date',
        ];
    }
}
