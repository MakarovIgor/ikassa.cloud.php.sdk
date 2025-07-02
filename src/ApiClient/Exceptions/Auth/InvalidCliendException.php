<?php

namespace igormakarov\IKassa\ApiClient\Exceptions\Auth;

use Exception;
use Throwable;

class InvalidCliendException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message);
    }
}