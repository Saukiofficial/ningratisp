<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Mengatur agar widget ini tampil paling atas (urutan 1)
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            // 1. Statistik Total Aset
            Stat::make('Total Aset', Asset::count())
                ->description('Semua perangkat terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]), // Grafik dummy pemanis

            // 2. Statistik Aset Tersedia (Gudang)
            Stat::make('Stok Gudang', Asset::where('status', 'available')->count())
                ->description('Perangkat siap pasang')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            // 3. Statistik Aset Terpasang
            Stat::make('Terpasang', Asset::where('status', 'in_use')->count())
                ->description('Sedang dipakai pelanggan')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('warning'),

            // 4. Total Nilai Aset (Rupiah)
            Stat::make('Total Nilai Aset', 'Rp ' . number_format(Asset::sum('price'), 0, ',', '.'))
                ->description('Estimasi valuasi inventaris')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),
        ];
    }
}
