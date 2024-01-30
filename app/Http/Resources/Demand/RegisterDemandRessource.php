<?php

namespace App\Http\Resources\Demand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterDemandRessource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name_project' => $this->name_project,
            'url' => $this->url,
            'user_id' => $this->user_id,
            'id' => $this->id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
