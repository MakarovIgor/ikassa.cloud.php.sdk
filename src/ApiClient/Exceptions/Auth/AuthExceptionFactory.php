<?php

namespace igormakarov\IKassa\ApiClient\Exceptions\Auth;

use Exception;

class AuthExceptionFactory
{
    /**
     * @throws Exception
     */
    public static function newInstance(string $code, string $message)
    {
        throw new Exception($code);
//        switch ($code) {
//            case 'invalid_client':
//                throw new Exception($code);
//            //return new InvalidCliendException();
//            case 'authorization_pending':
//                throw new Exception($code);
//        }
    }
}