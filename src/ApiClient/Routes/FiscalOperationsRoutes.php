<?php

namespace igormakarov\IKassa\ApiClient\Routes;

use igormakarov\IKassa\ApiClient\Models\FiscalDocumentData;
use igormakarov\IKassa\ApiClient\Models\Receipt;
use igormakarov\IKassa\ApiClient\Models\RefundReceipt;
use igormakarov\IKassa\ApiClient\Models\RollbackFiscalDocumentData;
use igormakarov\IKassa\AuthData;
use igormakarov\IKassa\Routes\Route;

class FiscalOperationsRoutes
{
    private string $url;
    private AuthData $authData;

    public function __construct(AuthData $authData)
    {
        $this->authData = $authData;
        $this->url = $authData->getUrl() . '/api/workstations.doc';
    }

    public function deposit(FiscalDocumentData $fiscalDocumentData): Route
    {
        $body = ['body' => $fiscalDocumentData->toJson()];
        return new Route($this->url . '.deposit', "POST", $body);
    }

    public function withdraw(FiscalDocumentData $fiscalDocumentData): Route
    {
        $body = ['body' => $fiscalDocumentData->toJson()];
        return new Route($this->url . '.withdraw', "POST", $body);
    }

    public function cHWithdraw(FiscalDocumentData $fiscalDocumentData): Route
    {
        $body = ['body' => $fiscalDocumentData->toJson()];
        return new Route($this->url . '.chWithdraw', "POST", $body);
    }

    public function sale(Receipt $receipt): Route
    {
        $body = ['body' => $receipt->toJson()];
        return new Route($this->url . '.sale', "POST", $body);
    }

    public function rollback(RollbackFiscalDocumentData $rollbackFiscalDocumentData): Route
    {
        $body = ['body' => $rollbackFiscalDocumentData->toJson()];
        return new Route($this->url . '.rollback', "POST", $body);
    }

    public function refund(RefundReceipt $receipt): Route
    {
        $body = ['body' => $receipt->toJson()];
        return new Route($this->url . '.refund', "POST", $body);
    }
}