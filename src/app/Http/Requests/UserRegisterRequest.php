<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
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
            "username" => 'required|string|unique:users|max:255',
            "first_name" => 'required|string|max:255',
            "last_name" => 'required|string|max:255',
            "email" => 'required|email|unique:users|max:255',
            "password" => 'required|min:6|max:255'
        ];
    }
}
