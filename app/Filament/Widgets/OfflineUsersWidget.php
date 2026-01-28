<?php

namespace App\Filament\Widgets;

use App\Helpers\MikrotikAPINative;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class OfflineUsersWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected bool $isConnected = true;
    protected string $connectionError = '';
    protected static bool $isDiscovered = false;
    protected ?int $total = 0;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $mikrotik = new MikrotikAPINative();
        $this->isConnected = $mikrotik->isConnected();

        return $table
            ->records(function (int $page, int $recordsPerPage, array $filters, ?string $search, ?string $sortColumn, ?string $sortDirection) use ($mikrotik): LengthAwarePaginator {
                try {

                    // Test connection by trying to get secrets
                    $secrets = $mikrotik->getPppSecrets();

                    if (empty(cache('ppp_active'))) {
                        $response = $mikrotik->getPppActive();
                        if (is_array($response) && !isset($response['error'])) {
                            Cache::remember('ppp_active', now()->addMinutes(5), fn() => $response);
                        }
                    }
                    $activePpp = cache('ppp_active');

                    $activeUsers = collect($activePpp)->pluck('name')->all();

                    $this->connectionError = 'Router not connected';

                    $collection = collect($secrets)->filter(function ($secret) use ($activeUsers) {
                        if (!isset($secret['name']) || !isset($secret['last-logged-out'])) {
                            return false;
                        }

                        return !in_array($secret['name'], $activeUsers);
                    })->map(function ($secret) {
                        // Convert array to object for easier column access
                        $lastLoggedOut = Carbon::parse($secret['last-logged-out']);
                        $secret['last-logged-out'] = $lastLoggedOut;
                        $secret['status'] = $lastLoggedOut->diffInMonths() >= 1 ? 'expired' : 'offline';
                        return  $secret;
                    })
                        ->sortBy(
                            $sortColumn,
                            SORT_REGULAR,
                            $sortDirection === 'desc',
                        )
                        ->when(
                            filled($search),
                            fn(Collection $data): Collection => $data->filter(
                                fn(array $record): bool => str_contains(
                                    strtolower($record['name']),
                                    strtolower($search),
                                ),
                            ),
                        )
                        ->when(
                            filled($status = strtolower($filters['status']['value']) ?? null),
                            fn(Collection $data): Collection => $data->where('status', $status)
                        );
                    $this->total = $collection->count();
                } catch (\Exception $e) {
                    $this->isConnected = false;
                    $this->connectionError = $e->getMessage();

                    // Return dummy data to show connection status
                    $collection = collect([
                        [
                            'name' => 'Connection Error',
                            'last-logged-out' => 'Unable to connect to MikroTik',
                            'status' => 'error'
                        ]
                    ]);
                }

                $records = $collection->forPage($page, $recordsPerPage);
                return new LengthAwarePaginator($records, $collection->count(), $recordsPerPage, $page);
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
                    ->label('Last Active')
                    // ->formatStateUsing(function ($state, $record) {
                    //     if (!$this->isConnected) {
                    //         return $this->connectionError;
                    //     }

                    //     try {
                    //         return Carbon::parse($state)->format('Y-m-d H:i:s');
                    //     } catch (\Exception $e) {
                    //         return $state;
                    //     }
                    // })
                    ->color(function ($record) {
                        return isset($record->status) && $record->status === 'error' ? 'danger' : null;
                    })
                    ->since()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'expired' => 'Expired',
                        'offline' => 'Offline'
                    ])
                    ->default('offline')
            ])
            ->defaultSort('last-logged-out')
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
                    }),
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
