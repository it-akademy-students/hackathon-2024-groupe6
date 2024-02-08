<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'id' => 'required|int',
      'email' => 'required|string|email',
      'name' => 'required|string'
    ];
  }

}
