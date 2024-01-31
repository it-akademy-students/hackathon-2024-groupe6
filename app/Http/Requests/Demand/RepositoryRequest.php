<?php

namespace App\Http\Requests\Demand;

use Illuminate\Foundation\Http\FormRequest;

class RepositoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(auth('sanctum')->check()) return true;
        return false;
    }
    public function rules(): array
    {
        return [
            'name_project' => 'string|max:250',
            'url' => ['required', 'string'],
        ];
    }
}
