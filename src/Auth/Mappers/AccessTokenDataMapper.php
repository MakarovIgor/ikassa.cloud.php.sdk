<?php

namespace igormakarov\IKassa\Auth\Mappers;

use igormakarov\IKassa\Auth\Models\AccessTokenData;

class AccessTokenDataMapper
{
    public static function newInstance(array $bindingData)
    {
        var_dump($bindingData);exit;
        return new AccessTokenData(
            $bindingData['access_token'],
            $bindingData['expires_in'],
            $bindingData['refresh_token']
        );
    }
}