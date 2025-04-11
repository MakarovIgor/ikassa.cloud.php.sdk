<?php

namespace igormakarov\IKassa\Auth\Mappers;

use igormakarov\IKassa\Auth\Models\BindingData;

class BindingDataMapper
{
    public static function newInstance(array $bindingData)
    {
        return new BindingData($bindingData['device_code'], $bindingData['expires_in'], $bindingData['user_code']);
    }
}