<?php

namespace App\Http\Requests\Auth;

class RegisterRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    return true;
  }


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:250',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ];
    }
}
