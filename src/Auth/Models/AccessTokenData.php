<?php

namespace igormakarov\IKassa\Auth\Models;

class AccessTokenData
{
    private string $accessToken;
    private int $expiresIn;
    private string $refreshToken;

    public function __construct(string $accessToken, int $expiresIn, string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }
}
