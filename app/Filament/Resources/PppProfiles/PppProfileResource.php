<?php

namespace App\Filament\Resources\PppProfiles;

use App\Filament\Resources\PppProfiles\Pages\CreatePppProfile;
use App\Filament\Resources\PppProfiles\Pages\EditPppProfile;
use App\Filament\Resources\PppProfiles\Pages\ListPppProfiles;
use App\Filament\Resources\PppProfiles\Pages\ViewPppProfile;
use App\Filament\Resources\PppProfiles\Schemas\PppProfileForm;
use App\Filament\Resources\PppProfiles\Schemas\PppProfileInfolist;
use App\Filament\Resources\PppProfiles\Tables\PppProfilesTable;
use App\Models\PppProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Helpers\MikrotikAPINative;
use UnitEnum;

class PppProfileResource extends Resource
{
    protected static ?string $model = PppProfile::class;

    protected static string | UnitEnum | null $navigationGroup = 'Master';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-server';

    public static function form(Schema $schema): Schema
    {
        return PppProfileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PppProfileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PppProfilesTable::configure($table)
            ->headerActions([
                Action::make('syncWithMikrotik')
                    ->label('Sync Profiles to MikroTik')
                    ->action(function () {
                        $mikrotik = new MikrotikAPINative();
                        $mikrotikProfiles = collect($mikrotik->getPppProfiles());

                        $localProfiles = PppProfile::all();
                        $syncedCount = 0;

                        foreach ($mikrotikProfiles as $mikrotikProfile) {
                            $profile = PppProfile::firstOrNew(['profile_name' => $mikrotikProfile['name']]);

                            if (!$profile->exists) {
                                $profile->profile_name = $mikrotikProfile['name'];
                                $profile->local_address = $mikrotikProfile['local-address'] ?? null;
                                $profile->remote_address = $mikrotikProfile['remote-address'] ?? null;
                                $profile->dns_server = $mikrotikProfile['dns-server'] ?? null;
                                $profile->rate_limit = $mikrotikProfile['rate-limit'] ?? null;
                                $profile->session_timeout = $mikrotikProfile['session-timeout'] ?? null;
                                $profile->idle_timeout = $mikrotikProfile['idle-timeout'] ?? null;
                                $profile->only_one = ($mikrotikProfile['only-one'] ?? 'false') === 'true';
                                $profile->is_active = ($mikrotikProfile['disabled'] ?? 'false') === 'false';

                                $profile->save();
                                $syncedCount++;
                            }
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('PPP Profiles synced with MikroTik')
                            ->body("{$syncedCount} new profiles added to MikroTik.")
                            ->success()
                            ->send();
                    }),
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
            'index' => ListPppProfiles::route('/'),
            'create' => CreatePppProfile::route('/create'),
            'view' => ViewPppProfile::route('/{record}'),
            'edit' => EditPppProfile::route('/{record}/edit'),
        ];
    }
}
