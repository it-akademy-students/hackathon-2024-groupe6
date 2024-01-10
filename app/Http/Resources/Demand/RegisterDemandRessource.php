<?php

namespace App\Http\Resources\Demand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterDemandRessource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
           //TODO : champs de DemandRessource a ajouter
        ];
    }
}
