<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:4', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'country_code' => ['required', 'string', 'max:4'],
            'mobile' => ['required', 'string', 'min:10', 'max:10'],
            'password' => ['required', 'confirmed', Password::defaults()],

        ];
    }
}
