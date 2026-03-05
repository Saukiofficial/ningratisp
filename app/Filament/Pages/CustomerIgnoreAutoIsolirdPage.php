<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerIgnoreAutoIsolirdPage extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected string $view = 'filament.pages.customer-ignore-auto-isolird-page';

    protected static bool $isDiscovered = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(Customer::query()->orderByRaw('INET_ATON(remote_address)'))
            ->filters([
                SelectFilter::make('remote_address')
                    ->options([
                        '192.168.10' => '.10',
                        '192.168.20' => '.20',
                    ])
                    ->query(
                        fn ($data, Builder $query) => $query->where('remote_address', 'LIKE', $data['value'].'%')
                    )
                    ->default('192.168.10'),
            ])
            ->columns([
                TextColumn::make('username')
                    ->description(fn (Customer $record) => $record->remote_address.' ('.$record->pppProfile->profile_name.')')
                    ->searchable(),
                ToggleColumn::make('auto_isolir'),
            ]);
    }
}
