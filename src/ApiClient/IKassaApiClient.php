<?php

namespace igormakarov\IKassa\ApiClient;

use Exception;
use igormakarov\IKassa\ApiClient\Models\Currencies;
use igormakarov\IKassa\ApiClient\Models\FiscalDocumentData;
use igormakarov\IKassa\ApiClient\Models\Receipt;
use igormakarov\IKassa\ApiClient\Models\RefundReceipt;
use igormakarov\IKassa\ApiClient\Models\RollbackFiscalDocumentData;
use igormakarov\IKassa\ApiClient\Routes\FiscalOperationsRoutes;
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
            ['Authorization' => 'Bearer ' . $authData->getToken(),
                'Content-Type' => 'application/json'],
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
    public function isConnected(): bool
    {
        $result = $this->httpClient->sendRequest((new ShiftRoutes($this->authData))->isConnected());
        return $result['hasDevice'] === true;
    }

    /**
     * @throws Throwable
     */
    public function getShift(): array
    {
        return $this->httpClient->sendRequest((new ShiftRoutes($this->authData))->shiftInfo());
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

    /**
     * @throws Throwable
     */
    public function deposit(FiscalDocumentData $fiscalDocumentData)
    {
        $this->httpClient->sendRequest((new FiscalOperationsRoutes($this->authData))->deposit($fiscalDocumentData));
    }

    /**
     * @throws Throwable
     */
    public function withdraw(FiscalDocumentData $fiscalDocumentData)
    {
        $this->httpClient->sendRequest((new FiscalOperationsRoutes($this->authData))->withdraw($fiscalDocumentData));
    }

    /**
     * @throws Throwable
     */
    public function cHWithdraw(FiscalDocumentData $fiscalDocumentData)
    {
        $this->httpClient->sendRequest((new FiscalOperationsRoutes($this->authData))->cHWithdraw($fiscalDocumentData));
    }

    /**
     * @throws Throwable
     */
    public function getCashSumInCashBox(string $currency): int
    {
        if (!in_array($currency, Currencies::toArray())) {
            throw new Exception("wrong currency, see Currencies::class");
        }
        $shift = $this->getShift();
        return isset($shift["counters"][$currency]) ? $shift["counters"][$currency]["attributes"]["$.cashin.sum"] : 0;
    }


    /**
     * @throws Throwable
     */
    public function sale(Receipt $receipt)
    {
        var_dump($this->httpClient->sendRequest((new FiscalOperationsRoutes($this->authData))->sale($receipt)));
    }

    /**
     * @throws Throwable
     */
    public function rollback(RollbackFiscalDocumentData $rollbackFiscalDocumentData)
    {
        var_dump($this->httpClient->sendRequest(
            (new FiscalOperationsRoutes($this->authData))->rollback($rollbackFiscalDocumentData)
        ));
    }

    /**
     * @throws Throwable
     */
    public function refund(RefundReceipt $receipt)
    {
        var_dump($this->httpClient->sendRequest(
            (new FiscalOperationsRoutes($this->authData))->refund($receipt)
        ));
    }

}