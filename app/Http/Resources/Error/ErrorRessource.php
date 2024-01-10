<?php

namespace App\Http\Resources\Error;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorRessource extends JsonResource
{
    /** @var \Exception $exception */
    private \Exception $exception;

    public function __construct(\Exception $exception = null)
    {
        $this->exception = $exception;
        parent::__construct($exception);
    }



    public function toArray(Request $request): array
    {
        return [
            'message' => 'Une erreur est survenue',
            'error' => $this->exception->message ?? 'ninjaaaa !'
        ];
    }
}
