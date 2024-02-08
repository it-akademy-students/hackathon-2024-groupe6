<?php

namespace App\Http\Requests\TestRequest;

use Illuminate\Foundation\Http\FormRequest;

class TestRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        //return true;
        if(auth('sanctum')->check()) return true;
        return false;
    }
    
    public function rules(): array
    {
        return [
          'branch' => 'required|string', 
          'tests.phpstan' => $this->composer_audit == false && $this->php_security_checker == false ? 'accepted|boolean' : 'nullable',
          'tests.composer_audit' => $this->phpstan == false && $this->php_security_checker == false ? 'accepted|boolean' : 'nullable',
          'tests.php_security_checker' => $this->phpstan == false && $this->composer_audit == false ? 'accepted|boolean' : 'nullable',
        ];
    }
}
