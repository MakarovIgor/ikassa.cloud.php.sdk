<?php

namespace igormakarov\IKassa\ApiClient\Exceptions\Shift;

use Exception;
use Throwable;

class CloseShiftException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}