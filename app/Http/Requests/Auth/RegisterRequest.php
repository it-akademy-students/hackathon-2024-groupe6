<?php

namespace App\Http\Requests\Auth;

class RegisterRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function validate(array $rules, ...$params): array
    {
        return [
            'name' => 'required|string|max:250',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ];
    }
}
