<?php

namespace igormakarov\IKassa\RecieptRenderer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FiscalDocumentRender
{
    private string $url;
    private Client $httpClient;

    public function __construct(string $environmentUrl)
    {
        $this->httpClient = new Client();
        $this->url = $environmentUrl;
    }

    /**
     * @throws GuzzleException
     */
    public function render(string $id): string
    {
        $response = $this->httpClient->request("GET", $this->url . "render/{$id}");
        return $response->getBody()->getContents();
    }
}