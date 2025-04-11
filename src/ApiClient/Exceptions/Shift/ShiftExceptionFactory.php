<?php

namespace igormakarov\IKassa\ApiClient\Exceptions\Shift;

class ShiftExceptionFactory
{
    public static function newInstance(string $code, string $message)
    {
       /* switch ($code) {
            case 'kernel.shift.opened':
                return new ShiftAlreadyOpenException($message);
        }*/
    }
}