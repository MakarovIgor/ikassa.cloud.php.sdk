<?php

namespace igormakarov\IKassa\Routes;

class Route
{
    private string $url;
    private string $method;
    private array $body;

    public function __construct(string $url, string $method = 'GET', array $body = [])
    {
        $this->url = $url;
        $this->method = $method;
        $this->body = $body;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function body(): array
    {
        return $this->body;
    }
}