<?php

namespace App\Http\Requests\TestRequest;

use Illuminate\Foundation\Http\FormRequest;

class TestRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (auth('sanctum')->check()) {
            return true;
        }
        return false;
    }

    public function rules(): array
    {
        return [
            'branch' => 'required|string',
            'tests.phpstan' => $this->tests['composer_audit'] == false && $this->tests['php_security_checker'] == false ? 'accepted|boolean' : 'nullable',
            'tests.composer_audit' => $this->tests['phpstan'] == false && $this->tests['php_security_checker'] == false ? 'accepted|boolean' : 'nullable',
            'tests.php_security_checker' => $this->tests['phpstan'] == false && $this->tests['composer_audit'] == false ? 'accepted|boolean' : 'nullable',
        ];
    }
}
