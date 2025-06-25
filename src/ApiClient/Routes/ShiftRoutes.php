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
        $this->url = $authData->getUrl() . '/api/workstations';
    }

    public function isConnected(): Route
    {
        return new Route($this->url . '.cashbox?skipShift=true');
    }

    public function shiftInfo(): Route
    {
        return new Route($this->url . '.shift');
    }

    public function openShift(): Route
    {
        return new Route($this->url . '.shift.open', "POST");
    }

    public function closeShift(): Route
    {
        return new Route($this->url . '.shift.close', "POST");
    }

    public function getFiscalDocumentByUid(string $uid): Route
    {
        return new Route($this->url . '.docs.get?uid=' . $uid);
    }
}