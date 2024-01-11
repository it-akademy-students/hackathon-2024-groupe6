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
    public function __construct(\Exception $exception = new \Exception())
    {
        $this->exception = $exception;
        parent::__construct($exception);
    }

    //region METHOD
    public function toArray(Request $request): array
    {
        return [
            'label' => 'Une erreur est survenue. Pour plus des details, veuillez consulter le Error Message',
            'errorMessage' =>  $this -> customMessage ?? $this -> exception -> getMessage(),
            'errorCode' => $this -> customCode ?? $this -> exception -> getCode()
        ];
    }
}
