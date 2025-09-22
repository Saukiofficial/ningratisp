<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Models\CustomerPackages;
use App\Models\Packages;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Attributes\On;

class CustomerPackagesRelationManager extends RelationManager
{
    protected static string $relationship = 'customerPackages';

    protected static ?string $title = 'Package';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('package_id')
                    ->label('Package')
                    ->options(Packages::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date'),
                Forms\Components\Select::make('status')
                    ->options(CustomerPackages::getStatusLabel())
                    ->required(),
                Forms\Components\Toggle::make('auto_renew')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('package.name')
            ->columns([
                Tables\Columns\TextColumn::make('package.name')->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => CustomerPackages::STATUS_ACTIVE,
                        'warning' => CustomerPackages::STATUS_SUSPENDED,
                        'danger' => CustomerPackages::STATUS_CANCELLED,
                        'gray' => CustomerPackages::STATUS_EXPIRED,
                    ]),
                Tables\Columns\IconColumn::make('auto_renew')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('add_package')
                    ->schema([
                        Select::make('package_id')
                            ->required()
                            ->options(Packages::pluck('name', 'id'))
                            ->rules([
                                fn(RelationManager $livewire, string $operation): \Closure =>
                                function (string $attribute, $value, \Closure $fail) use ($livewire, $operation) {
                                    $query = $livewire->getOwnerRecord()->customerPackages()->where('package_id', $value);

                                    if ($operation === 'edit') {
                                        $query->where('id', '!=', $livewire->getRecord()->id);
                                    }

                                    if ($query->exists()) {
                                        $fail('The package has already been assigned to this customer.');
                                    }
                                },
                            ])
                            ->afterStateUpdated(
                                fn($state, Set $set, Select $component) => $set(
                                    'package_name',
                                    $component->getOptionLabel($state)
                                )
                            ),
                        Hidden::make('package_name'),
                        DatePicker::make('start_date')
                            ->required()
                            ->default(now())
                            ->minDate(now()->format('Y-m-d'))
                            ->disabled()
                            ->dehydrated(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                    ])
                    ->action(function ($data) {
                        $data['status'] = $data['is_active'] === true ? CustomerPackages::STATUS_ACTIVE : CustomerPackages::STATUS_SUSPENDED;
                        $record = $this->getOwnerRecord()->customerPackages()->create($data);
                        if (
                            $record->start_date->format('Y-m-d') == now()->format('Y-m-d') &&
                            $record->status === CustomerPackages::STATUS_ACTIVE
                        ) {
                            $this->getOwnerRecord()->customerPackages()
                                ->where('id', '!=', $record->id)
                                ->where('status', CustomerPackages::STATUS_ACTIVE)
                                ->update([
                                    'status' => CustomerPackages::STATUS_SUSPENDED,
                                    'end_date' => now()
                                ]);
                        }

                        // TODO: dispatch job handle incoming new package more than now

                        Notification::make()
                            ->title('Success add package')
                            ->body('Package : ' . $data['package_name'])
                            ->success()
                            ->actions([
                                Action::make('undo')
                                    ->color('gray')
                                    ->button()
                                    ->dispatch('undoPackage', ['id' => $record->id])
                            ])
                            ->send();
                    }),
            ])
            ->recordActions([
                Action::make('activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(CustomerPackages $record) => $record->status !== CustomerPackages::STATUS_ACTIVE)
                    ->requiresConfirmation()
                    ->action(function (CustomerPackages $record) {
                        $record->customer->customerPackages()
                            ->where('status', CustomerPackages::STATUS_ACTIVE)
                            ->update([
                                'status' => CustomerPackages::STATUS_SUSPENDED,
                                'end_date' => now()
                            ]);

                        $record->update([
                            'status' => CustomerPackages::STATUS_ACTIVE,
                            'end_date' => null
                        ]);

                        Notification::make()
                            ->title('Package activated')
                            ->success()
                            ->send();
                    }),
                // Action::make('deactivate')
                //     ->icon('heroicon-o-x-circle')
                //     ->color('danger')
                //     ->visible(fn(CustomerPackages $record) => $record->status === CustomerPackages::STATUS_ACTIVE)
                //     ->requiresConfirmation()
                //     ->action(function (CustomerPackages $record) {
                //         $record->update(['status' => CustomerPackages::STATUS_SUSPENDED]);

                //         Notification::make()
                //             ->title('Package deactivated')
                //             ->success()
                //             ->send();
                //     }),

            ])
            ->defaultSort('status');
    }

    #[On('undoPackage')]
    public function undoPackage(int $id)
    {
        $record = CustomerPackages::find($id);
        if ($record) {
            $package = $record->package->name;
            $record->delete();
            $this->getOwnerRecord()->latestCustomerPackage()->update([
                'end_date' => null,
                'status' => CustomerPackages::STATUS_ACTIVE
            ]);
            Notification::make()
                ->title('Canceled')
                ->body("Package {$package} has been canceled")
                ->success()
                ->send();
        }
    }
}
