<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;

class RegisterResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function toArray(Request $request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "access_token" => $this->createToken("access_token")->plainTextToken,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
