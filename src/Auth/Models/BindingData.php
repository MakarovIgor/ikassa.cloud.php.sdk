<?php

namespace igormakarov\IKassa\Auth\Models;

class BindingData
{
    private string $deviceCode;
    private int $expiresIn;
    private string $userCode;

    public function __construct(string $deviceCode, int $expiresIn, string $userCode)
    {
        $this->deviceCode = $deviceCode;
        $this->expiresIn = $expiresIn;
        $this->userCode = $userCode;
    }

    /**
     * @return string
     */
    public function getDeviceCode(): string
    {
        return $this->deviceCode;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getUserCode(): string
    {
        return $this->userCode;
    }
}