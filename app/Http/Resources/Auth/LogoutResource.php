<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class LogoutResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      "id" => '',
      "name" => '',
      "email" => '',
      "access_token" => '',
      "created_at" => '',
      "updated_at" => '',
    ];
  }

}
