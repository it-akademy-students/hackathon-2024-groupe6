<?php

namespace App\Http\Resources\TestRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestRequestResource extends JsonResource
{
    private string $message;

    private $data;

    public function __construct($message, $data)
    {
        $this->message = $message;
        $this->data = $data;
        parent::__construct($message, $data);
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => true,
            'message' => $this->message,
            'data' => [
                $this->data,
                'phpstan_result' => [
                    'status' => [
                        'status' => '',
                    ],
                ],
                'composer_audit_result' => [
                    'status' => [
                        'status' => '',
                    ],
                ],
                'php_security_checker_result' => [
                    'status' => [
                        'status' => '',
                    ],
                ],
            ],
        ];
    }

    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->setStatusCode(200);
    }
}
