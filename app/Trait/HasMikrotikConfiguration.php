<?php

namespace App\Trait;

trait HasMikrotikConfiguration
{
    protected bool $shouldLog = true;
    protected int $timeout = 0;

    public function setShouldLog($state = true): void
    {
        $this->shouldLog = $state;
    }

    public function getShouldLog(): bool
    {
        return $this->shouldLog;
    }

    public function setRequestTimeout(int $seconds): void
    {
        $this->timeout = $seconds;
    }

    public function getRequestTimeout(): int
    {
        return $this->timeout;
    }
}
