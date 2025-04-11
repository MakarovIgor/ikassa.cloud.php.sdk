<?php

namespace igormakarov\IKassa\ApiClient\Routes;

use igormakarov\IKassa\AuthData;
use igormakarov\IKassa\Routes\Route;

class ShiftRoutes
{
    private string $url;
    private AuthData $authData;

    public function __construct(AuthData $authData)
    {
        $this->authData = $authData;
        $this->url = $authData->getUrl() . '/api/workstations.shift';
    }

    public function shiftInfo()
    {
        return new Route($this->url);
    }

    public function openShift()
    {
        return new Route($this->url . '.open', "POST");
    }

    public function closeShift()
    {
        return new Route($this->url . '.close', "POST");
    }
}