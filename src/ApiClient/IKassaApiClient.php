<?php

namespace igormakarov\IKassa\ApiClient;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use igormakarov\IKassa\ApiClient\Routes\ShiftRoutes;
use igormakarov\IKassa\AuthData;
use igormakarov\IKassa\Routes\Route;
use Throwable;

class IKassaApiClient
{
    private AuthData $authData;
    private Client $httpClient;

    public function __construct(AuthData $authData)
    {
        $this->authData = $authData;
        $this->httpClient = new Client(['headers' => [
            'Authorization' => 'Bearer ' . $authData->getToken()
        ]]);
    }

    /**
     * @throws Throwable
     */
    public function getShift()
    {
        var_dump($this->sendRequest((new ShiftRoutes($this->authData))->shiftInfo()));
    }

    /**
     * @throws Throwable
     */
    public function shiftIsOpen()
    {
        $result = $this->sendRequest((new ShiftRoutes($this->authData))->shiftInfo());
        return $result != null && $result['document'] == null;
    }


    /**
     * @throws Throwable
     */
    public function openShift()
    {
        $this->sendRequest((new ShiftRoutes($this->authData))->openShift());
    }


    /**
     * @throws Throwable
     */
    public function closeShift()
    {
        $this->sendRequest((new ShiftRoutes($this->authData))->closeShift());
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

            if (!empty($result['code']) && !empty($result['message'])) {
                throw new Exception($result['message']);
            }
            return $result;
        } catch (Throwable $exception) {
            throw new Exception($exception->getCode(), $exception->getMessage());
        }
    }

}