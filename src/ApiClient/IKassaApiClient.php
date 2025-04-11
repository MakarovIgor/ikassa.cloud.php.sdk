<?php

namespace igormakarov\IKassa\ApiClient;

use Exception;
use igormakarov\IKassa\ApiClient\Routes\ShiftRoutes;
use igormakarov\IKassa\AuthData;
use igormakarov\IKassa\HttpClient;
use Throwable;

class IKassaApiClient
{
    private AuthData $authData;
    private HttpClient $httpClient;

    public function __construct(AuthData $authData)
    {
        $this->authData = $authData;
        $this->httpClient = new HttpClient(
            ['Authorization' => 'Bearer ' . $authData->getToken()],
            function (array $result) {
                if (!empty($result['code']) && !empty($result['message'])) {
                    throw new Exception($result['message']);
                }
            }
        );
    }

    /**
     * @throws Throwable
     */
    public function getShift(): void
    {
        var_dump($this->httpClient->sendRequest((new ShiftRoutes($this->authData))->shiftInfo()));
    }

    /**
     * @throws Throwable
     */
    public function shiftIsOpen(): bool
    {
        $result = $this->httpClient->sendRequest((new ShiftRoutes($this->authData))->shiftInfo());
        return $result != null && $result['document'] == null;
    }

    /**
     * @throws Throwable
     */
    public function openShift(): void
    {
        $this->httpClient->sendRequest((new ShiftRoutes($this->authData))->openShift());
    }

    /**
     * @throws Throwable
     */
    public function closeShift()
    {
        $this->httpClient->sendRequest((new ShiftRoutes($this->authData))->closeShift());
    }

}