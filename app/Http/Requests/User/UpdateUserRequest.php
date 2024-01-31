<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (auth('sanctum')->check()) return true;
        return false;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:250',
            'email' => 'required|string|email|max:250',
            'password' => 'sometimes|string|min:8|confirmed',
            'address' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'tel' => 'nullable|string',
            'description' => 'nullable|string'

        ];
    }
}
