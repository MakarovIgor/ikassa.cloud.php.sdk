<?php

namespace igormakarov\IKassa;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use igormakarov\IKassa\Routes\Route;
use Throwable;

class HttpClient
{
    private Client $httpClient;

    private $callBackRequestExceptionFunction;

    public function __construct(array $headers, $callBackRequestExceptionFunction = null)
    {
        $config = (!empty($headers)) ? ['headers' => $headers] : [];
        $this->httpClient = new Client($config);
        $this->callBackRequestExceptionFunction = $callBackRequestExceptionFunction;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function sendRequest(Route $route): array
    {
        try {
            $response = $this->httpClient->request($route->method(), $route->url(), $route->body());
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $exception) {
            $result = json_decode($exception->getResponse()->getBody()->getContents(), true);

            if (is_callable($this->callBackRequestExceptionFunction)) {
                $callBack = $this->callBackRequestExceptionFunction;
                $callBack($result);
            }

            return $result;
        } catch (Throwable $exception) {
            throw new Exception($exception->getCode(), $exception->getMessage());
        }
    }
}