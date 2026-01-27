<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AccountsReceivableChart;
use App\Filament\Widgets\FinancialStatsOverview;
use App\Filament\Widgets\MRRStats;
use App\Filament\Widgets\NetRevenueChart;
use App\Filament\Widgets\PaymentsByMethodChart;
use App\Filament\Widgets\RevenueOverviewChart;
use App\Filament\Widgets\VirtualAccountStatusChart;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class DashboardPage extends Dashboard
{
    public function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Data')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $this->dispatch('refresh-dashboard');
                    Notification::make()
                        ->title('Dashboard Refreshed')
                        ->body('The widget data has been updated.')
                        ->success()
                        ->send();
                })
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            FinancialStatsOverview::class,
            MRRStats::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            RevenueOverviewChart::class,
            NetRevenueChart::class,
            PaymentsByMethodChart::class,
            VirtualAccountStatusChart::class,
            AccountsReceivableChart::class,
            AccountWidget::class,
            FilamentInfoWidget::class,
        ];
    }
}
