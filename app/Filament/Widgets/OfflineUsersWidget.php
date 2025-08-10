<?php

namespace App\Filament\Widgets;

use App\Helpers\MikrotikAPI;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Collection;

class OfflineUsersWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected bool $isConnected = true;
    protected string $connectionError = '';
    protected static bool $isDiscovered = false;

    public function table(Table $table): Table
    {
        $mikrotik = new MikrotikAPI();
        $this->isConnected = $mikrotik->isConnected();

        return $table
            ->records(function () use ($mikrotik): array {
                try {

                    // Test connection by trying to get secrets
                    $secrets = $mikrotik->getPppSecrets();
                    $activeUsers = collect($mikrotik->getPppActive())->pluck('name')->all();

                    $this->connectionError = 'Router not connected';

                    return collect($secrets)->filter(function ($secret) use ($activeUsers) {
                        if (!isset($secret['name']) || !isset($secret['last-logged-out'])) {
                            return false;
                        }

                        try {
                            $lastLoggedOut = Carbon::parse($secret['last-logged-out']);
                        } catch (\Exception $e) {
                            return false; // Ignore invalid date formats
                        }

                        return !in_array($secret['name'], $activeUsers) && $lastLoggedOut->diffInHours(now()) > 24;
                    })->map(function ($secret) {
                        // Convert array to object for easier column access
                        return  $secret;
                    })->values()->toArray();
                } catch (\Exception $e) {
                    $this->isConnected = false;
                    $this->connectionError = $e->getMessage();

                    // Return dummy data to show connection status
                    return [
                        [
                            'name' => 'Connection Error',
                            'last-logged-out' => 'Unable to connect to MikroTik',
                            'status' => 'error'
                        ]
                    ];
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Username')
                    ->searchable()
                    ->sortable()
                    ->color(function ($record) {
                        return isset($record->status) && $record->status === 'error' ? 'danger' : null;
                    })
                    ->icon(function ($record) {
                        if (!$this->isConnected) {
                            return 'heroicon-o-exclamation-triangle';
                        }
                        return 'heroicon-o-user';
                    }),
                Tables\Columns\TextColumn::make('last-logged-out')
                    ->label('Last Logged Out')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$this->isConnected) {
                            return $this->connectionError;
                        }

                        try {
                            return Carbon::parse($state)->format('Y-m-d H:i:s');
                        } catch (\Exception $e) {
                            return $state;
                        }
                    })
                    ->color(function ($record) {
                        return isset($record->status) && $record->status === 'error' ? 'danger' : null;
                    })
                    ->sortable(),
            ])
            ->defaultSort('last-logged-out', 'desc')
            ->paginated([10, 25, 50])
            ->poll('30s') // Auto-refresh every 30 seconds
            ->headerActions([
                Action::make('connection_status')
                    ->label($this->isConnected ? 'Connected' : 'Disconnected')
                    ->color($this->isConnected ? 'success' : 'danger')
                    ->icon(Heroicon::Wifi)
                    ->disabled()
                    ->tooltip(
                        $this->isConnected ? 'MikroTik connection is active' : 'Error: ' . $this->connectionError
                    ),
                Action::make('refresh')
                    ->label('Refresh')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function () {
                        // Force refresh by clearing any cache if needed
                        $this->dispatch('$refresh');
                    })
            ])
            ->emptyStateHeading($this->isConnected ? 'No Offline Users' : 'Connection Error')
            ->emptyStateDescription(
                $this->isConnected ?
                    'All users are currently online or have been active within the last 24 hours.' :
                    'Unable to connect to MikroTik router. Please check your connection settings.'
            )
            ->emptyStateIcon($this->isConnected ? 'heroicon-o-users' : 'heroicon-o-exclamation-triangle');
    }
}
