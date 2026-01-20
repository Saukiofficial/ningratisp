<?php

namespace App\Services\Midtrans;

interface NotificationHandlerInterface
{
    public function handle(string $orderId, array $data): bool;
}
