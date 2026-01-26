<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintResource\Pages;
use App\Models\Complaint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Import Komponen Form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
// Import Komponen Table
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';

    protected static ?string $navigationLabel = 'Pengaduan';

    protected static ?string $modelLabel = 'Tiket Pengaduan';

    protected static ?string $navigationGroup = 'Layanan Pelanggan';


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Tiket')
                    ->schema([

                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Pelanggan')
                            ->disabled()
                            ->required(),

                        TextInput::make('subject')
                            ->label('Judul Keluhan')
                            ->disabled()
                            ->required(),

                        Textarea::make('description')
                            ->label('Isi Laporan')
                            ->disabled()
                            ->columnSpanFull(),


                        Select::make('status')
                            ->label('Status Tiket')
                            ->options([
                                'pending' => 'Menunggu (Pending)',
                                'resolved' => 'Selesai (Resolved)',
                            ])
                            ->required()
                            ->native(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('subject')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'resolved' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'resolved' => 'Selesai',
                        default => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('Dilaporkan')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'resolved' => 'Selesai',
                    ]),
            ])
            ->actions([
                EditAction::make()
                    ->label('Proses'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplaints::route('/'),
            'create' => Pages\CreateComplaint::route('/create'),
            'edit' => Pages\EditComplaint::route('/{record}/edit'),
        ];
    }
}
