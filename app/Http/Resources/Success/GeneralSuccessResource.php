<?php

namespace App\Http\Resources\Success;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneralSuccessResource extends JsonResource
{
    /** @var string $message */
    private string $message;

    public function __construct($message)
    {
        $this->message = $message;
        parent::__construct($message);
    }

  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
    public function toArray(Request $request): array
    {
        return [
            'status' => true,
            'message' => $this->message
        ];
    }

  /**
   * @param Request $request
   * @param JsonResponse $response
   * @return void
   */
    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->setStatusCode(200);
    }
}
