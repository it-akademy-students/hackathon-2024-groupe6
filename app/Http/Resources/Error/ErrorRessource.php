<?php

namespace App\Http\Resources\Error;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorRessource extends JsonResource
{
    //region PROPERTIES
    /** @var \Exception $exception */
    private \Exception $exception;

    /** @var $customMessage */
    private string $customMessage;

    /** @var $customCode */
    private string $customCode;

    //region SETTERS
    /**
     * A setter for $customMessage
     */
    public function setMessage(String $message): void
    {
        $this->customMessage = $message;
    }

    /**
     * A setter for $customCode
     */
    public function setCode(int $code): void
    {
        $this->customCode = $code;
    }

    //region CONSTRUCTOR
    /**
     * Override constructor to accept parameter
     * @param \Exception|null $exception
     */
    public function __construct(\Exception $exception = null)
    {
        $this->exception = $exception;
        parent::__construct($exception);
    }

    //region METHODS
    public function toArray(Request $request): array
    {
        return [
            'label' => 'Une erreur est survenue',
            'errorMessage' => $this -> exception -> getMessage() ?? $this -> customMessage,
            'errorCode' => $this -> exception -> getCode() ?? $this -> customCode
        ];
    }
}
