<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use App\Models\Packages;
use App\Models\PppArea;
use App\Models\PppProfile;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateCustomer extends CreateRecord
{
    protected ?bool $hasDatabaseTransactions = true;

    protected static string $resource = CustomerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['form_create']) && Customer::query()->where('username', $data['username'])->exists()) {
            Notification::make()
                ->title('Username sudah terdaftar')
                ->danger()->send();
            $this->halt(true);
        }

        $package = Packages::find($data['package_id']);
        $data['ppp_profile_id'] = $package->ppp_profile_id;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // Save the record first so we have an ID to work with
            $record = static::getModel()::create($data);

            $mikrotik = new MikrotikAPINative;

            // 1. Check username exists on Mikrotik
            $username = $data['username'];
            $secrets_raw = $mikrotik->getPppSecrets(false);

            if (isset($secrets_raw['error'])) {
                Notification::make()
                    ->title('Mikrotik Connection Error')
                    ->body($secrets_raw['message'])
                    ->danger()->send();

                DB::rollBack();
                $this->halt();
            }

            $secrets = collect($secrets_raw);

            if ($secrets->where('name', $username)->isNotEmpty()) {
                Notification::make()
                    ->title('Username sudah terdaftar di Mikrotik')
                    ->danger()->send();

                DB::rollBack();
                $this->halt();
            }

            // 2. Calculate IPs
            $networkPrefix = $data['ip_network'];

            $mikrotikLocalIps = $secrets->pluck('local-address')
                ->filter(fn($ip) => $ip && str_starts_with($ip, $networkPrefix))
                ->map(fn($ip) => ($octet = str_replace($networkPrefix, '', $ip)) && is_numeric($octet) ? (int)$octet : null)
                ->filter();

            $databaseLocalIps = Customer::query()
                ->where('local_address', 'like', $networkPrefix . '%')
                ->where('id', '!=', $record->id) // exclude current record
                ->pluck('local_address')
                ->map(fn($ip) => ($octet = str_replace($networkPrefix, '', $ip)) && is_numeric($octet) ? (int)$octet : null)
                ->filter();

            $existingLocalIps = $mikrotikLocalIps->merge($databaseLocalIps)->unique()->sort();
            $localIpSuffix = $existingLocalIps->isEmpty() ? 1 : $existingLocalIps->last() + 1;
            $localIp = $networkPrefix . $localIpSuffix;

            $mikrotikRemoteIps = $secrets->pluck('remote-address')
                ->filter(fn($ip) => $ip && str_starts_with($ip, $networkPrefix))
                ->map(fn($ip) => ($octet = str_replace($networkPrefix, '', $ip)) && is_numeric($octet) ? (int)$octet : null)
                ->filter();

            $databaseRemoteIps = Customer::query()
                ->where('remote_address', 'like', $networkPrefix . '%')
                ->where('id', '!=', $record->id)
                ->pluck('remote_address')
                ->map(fn($ip) => ($octet = str_replace($networkPrefix, '', $ip)) && is_numeric($octet) ? (int)$octet : null)
                ->filter();

            $existingRemoteIps = $mikrotikRemoteIps->merge($databaseRemoteIps)->unique()->sort();
            $maxHostIp = max($localIpSuffix, $existingRemoteIps->isEmpty() ? $localIpSuffix : $existingRemoteIps->max());
            $nextIpSuffix = $maxHostIp + 1;

            if ($nextIpSuffix > 254) {
                Notification::make()
                    ->title('IP Address in this segment is full')
                    ->danger()->send();

                DB::rollBack();
                $this->halt();
            }

            $remoteIp = $networkPrefix . $nextIpSuffix;

            // 3. Check PPP Profile
            $pppProfile = PppProfile::find($data['ppp_profile_id']);

            if (!$pppProfile) {
                Notification::make()
                    ->title('PPP Profile not found')
                    ->danger()->send();

                DB::rollBack();
                $this->halt();
            }

            // 4. Create PPPoE account on Mikrotik
            $response = $mikrotik->createPpoeCustomer(
                $username,
                $pppProfile,
                $data['password_pptp'] ?? 12345,
                $localIp,
                $remoteIp
            );

            if (isset($response['error']) && $response['error']) {
                Notification::make()
                    ->title('Gagal membuat akun PPPoE di Mikrotik')
                    ->body($response['message'] ?? 'Unknown Error')
                    ->danger()->send();

                DB::rollBack();
                $this->halt();
            }

            // 5. Update record with IPs
            $record->update([
                'local_address' => $localIp,
                'remote_address' => $remoteIp,
                'service_name' => 'pppoe'
            ]);

            return $record;
        });
    }

    protected function afterCreate(): void
    {
        $this->record->customerPackages()->create([
            'package_id' => $this->data['package_id'],
            'start_date' => now(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        $areas = PppArea::all(['name', 'customer_prefix', 'network_prefix']);

        return $schema
            ->components([
                Section::make('Information')
                    ->description('Informasi basic mengenai customer')
                    ->schema([
                        Select::make('area')
                            ->options($areas->pluck('name', 'customer_prefix'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($get, $set, $state) use ($areas) {
                                $state = $state == '-' ? null : $state;
                                $set('username', $get('ppp_user') . $state);
                                $network = $areas->firstWhere('customer_prefix', $state)?->network_prefix ?? '';
                                $set('ip_network', $network);
                            }),
                        TextInput::make('full_name')
                            ->label('Nama Lengkap'),
                        TextInput::make('ppp_user')
                            ->label('Username')
                            ->required()
                            ->live(true)
                            ->afterStateUpdated(
                                fn($get, $set, $state) => $set('username', $state . $get('area'))
                            ),
                        Select::make('package_id')
                            ->label('Paket')
                            ->options(Packages::getDropDownWithPpp())
                            ->preload()
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $component) {
                                $set('package_selected', $component->getOptions()[$state] ?? null);
                                $set('package_id', $state);
                            }),
                        Hidden::make('ip_network'),
                        Hidden::make('password')->default(12345),
                        Hidden::make('form_create')->default(true),
                        TextInput::make('phone')
                            ->label('No. Whatsapp')
                            ->mask('9999-9999-9999')
                            ->placeholder('0812-3456-789'),
                    ]),
                Section::make('PPPOE Information')
                    ->description('Gunakan username dan password pada WAN Modem')
                    ->schema([
                        TextInput::make('package_selected')
                            ->label('Paket dipilih')
                            ->disabled()
                            ->live(),
                        TextInput::make('username')
                            ->label('Username')
                            ->disabled()
                            ->dehydrated()
                            ->copyable()
                            ->live(),
                        TextInput::make('password_pptp')
                            ->label('Password')
                            ->copyable()
                            ->dehydrated()
                            ->disabled()
                            ->default(12345)
                            ->live(),
                    ]),
            ]);
    }
}
