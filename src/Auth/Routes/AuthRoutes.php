<?php

namespace igormakarov\IKassa\Auth\Routes;

use igormakarov\IKassa\AuthData;
use igormakarov\IKassa\Routes\Route;

class AuthRoutes
{
    private string $url;
    private AuthData $authData;

    public function __construct(AuthData $authData)
    {
        $this->authData = $authData;
        $this->url = $authData->getUrl() . '/oauth/';
    }

    public function getBindingRoute(string $osName, string $osVersion, string $deviceName, string $appName): Route
    {
        $body = [
            'form_params' => [
                'client_id' => $this->authData->getToken(),
                'scope' => 'offline',
                '_os' => $osName,
                '_osVer' => $osVersion,
                '_device' => $deviceName,
                '_app' => $appName
            ]
        ];

        return new Route($this->url . 'device/code', "POST", $body);
    }

    public function getAccessTokenRoute(string $deviceCode): Route
    {
        $body = [
            'form_params' => [
                'client_id' => $this->authData->getToken(),
                'grant_type' => 'urn:ietf:params:oauth:grant-type:device_code',
                'device_code' => $deviceCode
            ]
        ];

        return new Route($this->url . 'token', "POST", $body);
    }

    public function getRefreshTokenRoute(string $refreshToken): Route
    {
        $body = [
            'form_params' => [
                'client_id' => $this->authData->getToken(),
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken
            ]
        ];

        return new Route($this->url . 'token', "POST", $body);
    }
}