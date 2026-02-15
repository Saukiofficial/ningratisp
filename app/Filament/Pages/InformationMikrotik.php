<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\OfflineUsersWidget;
use App\Filament\Widgets\OnlineOfflineUsersStatWidget;
use App\Filament\Widgets\TrafficWidget;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class InformationMikrotik extends Page
{
    protected string $view = 'filament.pages.information-mikrotik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected function getHeaderWidgets(): array
    {
        return [
            // TrafficWidget::class,
            OnlineOfflineUsersStatWidget::class,
            OfflineUsersWidget::class,
        ];
    }
}
