<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "adress" => $this->adress,
            "zip_code" => $this->zip_code,
            "city" => $this->city,
            "country" => $this->country,
            "tel" => $this->tel,
            "description" => $this->description,
            "access_token" => $this->createToken("access_token")->plainTextToken,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
