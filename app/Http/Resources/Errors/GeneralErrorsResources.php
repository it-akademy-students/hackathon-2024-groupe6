<?php

namespace App\Http\Resources\Errors;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Throwable;

class GeneralErrorsResources extends JsonResource
{
    /** @var Throwable $exception */
    private Throwable $exception;

    public function __construct($exception)
    {
        $this->exception = $exception;
        parent::__construct($exception);
    }

    public function toArray(Request $request)
    {
        return [
            'message' => 'Une erreur s\'est produite',
            'error' => $this->exception
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        if ($this->resource !== null) {
            $response->setStatusCode(404);
        }
    }
}
