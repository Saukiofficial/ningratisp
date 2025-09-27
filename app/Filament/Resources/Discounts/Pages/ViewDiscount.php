<?php

namespace App\Filament\Resources\Discounts\Pages;

use App\Filament\Resources\Discounts\DiscountResource;
use App\Filament\Resources\Discounts\RelationManagers\CustomerUsedVoucherRelationManager;
use App\Models\Discount;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ViewDiscount extends ViewRecord
{
    protected static string $resource = DiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Satu')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Basic Information')
                            ->columns(2)
                            ->schema([
                                // Section::make('Basic')
                                //     ->columns(2)
                                //     ->columnSpanFull()
                                //     ->components([
                                //     ]),
                                TextEntry::make('name'),
                                TextEntry::make('code')
                                    ->copyable()
                                    ->color('success')
                                    ->icon(Heroicon::OutlinedDocumentDuplicate),
                                TextEntry::make('start_date')
                                    ->date('d F Y'),
                                TextEntry::make('end_date')
                                    ->date('d F Y'),
                                TextEntry::make('description')
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Application Details')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('type')
                                    ->formatStateUsing(
                                        fn($state) => Discount::getAmountType()[$state]
                                    ),
                                TextEntry::make('applicable_to')
                                    ->formatStateUsing(
                                        fn($state) => Discount::getApplicableStatus()[$state]
                                    ),
                                TextEntry::make('customer_category')
                                    ->formatStateUsing(
                                        fn($state) => Discount::getCategoryStatus()[$state]
                                    ),
                                TextEntry::make('package.name'),
                                TextEntry::make('value')
                                    ->formatStateUsing(function ($state, $record) {
                                        if ($record->type === Discount::FIXED_AMOUNT) {
                                            return 'IDR ' . number_format($state, 0, ',', '.');
                                        }

                                        return intval($state) . '%';
                                    }),
                                TextEntry::make('max_discount_amount')
                                    ->money('IDR'),
                            ])
                    ]),
            ]);
    }

    public function getRelationManagers(): array
    {
        return [
            CustomerUsedVoucherRelationManager::class
        ];
    }
}
