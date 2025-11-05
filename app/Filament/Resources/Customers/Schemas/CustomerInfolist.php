<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('details')
                    ->schema([
                        TextEntry::make('billing_number'),
                        TextEntry::make('username'),
                        TextEntry::make('full_name'),
                        TextEntry::make('activePackage.package.name'),
                        TextEntry::make('invoices_count')->counts(
                            ['invoices' => fn (Builder $query) => $query->where('invoices.status', 'unpaid')]
                        )->label('Active invoice'),
                    ])
                    ->afterHeader([
                        \Filament\Schemas\Components\Text::make(
                            fn (Customer $record) => ! empty($record->isolir_at) ?
                                'Isolir' : 'Active'
                        )
                            ->color(
                                fn (Customer $record) => ! empty($record->isolir_at) ?
                                    'danger' : 'success'
                            )
                            ->icon(
                                fn (Customer $record) => ! empty($record->isolir_at) ?
                                    Heroicon::OutlinedSignalSlash : Heroicon::OutlinedSignal
                            )
                            ->badge(),
                    ])
                    ->columnSpanFull()
                    ->columns(3),
            ]);
    }
}
