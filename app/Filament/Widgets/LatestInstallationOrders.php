<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CustomerInstallations\CustomerInstallationResource;
use App\Models\CustomerInstallationOrder;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestInstallationOrders extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CustomerInstallationOrder::query()
                    ->whereIn('status', [CustomerInstallationOrder::STATUS_PENDING, CustomerInstallationOrder::STATUS_ON_PROGRESS])
                    ->latest('installation_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('area.name')
                    ->label('Area'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        CustomerInstallationOrder::STATUS_PENDING => 'gray',
                        CustomerInstallationOrder::STATUS_ON_PROGRESS => 'warning',
                        CustomerInstallationOrder::STATUS_DONE => 'success',
                        CustomerInstallationOrder::STATUS_CANCELLED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => CustomerInstallationOrder::getStatusLabel()[$state] ?? $state),
                Tables\Columns\TextColumn::make('installation_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->url(fn(CustomerInstallationOrder $record): string => CustomerInstallationResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
