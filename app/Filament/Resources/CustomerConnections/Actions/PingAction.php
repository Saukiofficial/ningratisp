<?php

namespace App\Filament\Resources\CustomerConnections\Actions;

use App\Helpers\MikrotikAPI;
use App\Models\CustomerConnection;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class PingAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->label('Ping')
            ->icon('heroicon-o-signal')
            ->action(function (CustomerConnection $record) {
                $mikrotik = new MikrotikAPI;
                $response = $mikrotik->ping($record->ip_address);

                if (isset($response['error'])) {
                    Notification::make()
                        ->title('Ping Failed')
                        ->body($response['message'])
                        ->danger()
                        ->send();

                    return;
                }

                $packetLoss = 0;
                foreach ($response as $line) {
                    if ($line['packet-loss'] > 0) {
                        $packetLoss = $line['packet-loss'];
                    }
                }

                $status = $packetLoss < 5 ? 'online' : 'offline';

                $record->update([
                    'status' => $status,
                    'ping_count' => $response[count($response) - 1]['received'] ?? 0,
                    'packet_loss' => $packetLoss,
                    'avg_rtt' => $response[count($response) - 1]['avg-rtt'] ?? null,
                    'last_seen' => now(),
                    'last_ping_output' => json_encode($response),
                ]);

                Notification::make()
                    ->title('Ping Successful')
                    ->body("Customer is {$status}")
                    ->success()
                    ->send();
            });
    }
}
