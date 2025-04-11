<?php

namespace igormakarov\IKassa\Auth;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use igormakarov\IKassa\Auth\Mappers\AccessTokenDataMapper;
use igormakarov\IKassa\Auth\Mappers\BindingDataMapper;
use igormakarov\IKassa\Auth\Models\AccessTokenData;
use igormakarov\IKassa\Auth\Models\BindingData;
use igormakarov\IKassa\Auth\Routes\AuthRoutes;
use igormakarov\IKassa\AuthData;
use igormakarov\IKassa\Routes\Route;
use Throwable;

class Auth
{
    private AuthData $authData;
    private Client $httpClient;
    private AuthRoutes $authRoutes;

    public function __construct(AuthData $authData)
    {
        $this->authData = $authData;
        $this->authRoutes = new AuthRoutes($this->authData);
        $this->httpClient = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded']]);
    }

    /**
     * @throws Throwable
     */
    public function bindingData(string $osName, string $osVersion, string $deviceName, string $appName): BindingData
    {
        var_dump($this->sendRequest($this->authRoutes->getBindingRoute($osName, $osVersion, $deviceName, $appName)));
        return BindingDataMapper::newInstance(
            $this->sendRequest($this->authRoutes->getBindingRoute($osName, $osVersion, $deviceName, $appName))
        );
    }

    public function getAccessTokenData(string $deviceCode): AccessTokenData
    {
        return AccessTokenDataMapper::newInstance(
            $this->sendRequest($this->authRoutes->getAccessTokenRoute($deviceCode))
        );
    }

    /**
     * @throws Throwable
     */
    public function refreshAccessTokenData(string $refreshToken): AccessTokenData
    {
        return AccessTokenDataMapper::newInstance(
            $this->sendRequest($this->authRoutes->getRefreshTokenRoute($refreshToken))
        );
    }

    /**
     * @throws Throwable
     */
    protected function sendRequest(Route $route): array
    {
        try {
            $response = $this->httpClient->request($route->method(), $route->url(), $route->body());
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $exception) {
            $result = json_decode($exception->getResponse()->getBody()->getContents(), true);
            if (!empty($result['error'])) {
                throw new Exception($result['error_description'], $exception->getCode());
            }
            return $result;
        } catch (Throwable $exception) {
            throw new Exception($exception->getCode(), $exception->getCode());
        }
    }

}