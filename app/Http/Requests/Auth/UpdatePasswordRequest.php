<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Set to true if no special authorization is needed.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define validation rules for updating the password.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ];
    }

    /**
     * Custom error messages (optional).
     */
    public function messages(): array
    {
        return [
            'new_password.different' => 'The new password must be different from the current password.',
        ];
    }
}
