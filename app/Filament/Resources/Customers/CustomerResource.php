<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Filament\Resources\Customers\RelationManagers\InvoicesRelationManager;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Schemas\CustomerInfolist;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Filament\Resources\Customers\Widgets\CustomerStats;
use App\Models\Customer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Helpers\MikrotikAPINative;
use App\Models\PppProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string | UnitEnum | null $navigationGroup = 'Master';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'username';

    protected static int $globalSearchResultsLimit = 20;

    public static function form(Schema $schema): Schema
    {
        return CustomerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table)
            ->headerActions([
                Action::make('syncWithMikrotik')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->label('Sync from MikroTik')
                    ->action(function () {
                        $mikrotik = new MikrotikAPINative();
                        $secrets = collect(
                            $mikrotik->getPppSecrets(fromCache: false)
                        );

                        $syncedCount = 0;

                        foreach ($secrets as $secret) {
                            $customer = Customer::firstOrNew(['username' => $secret['name']]);

                            if (!$customer->exists) {
                                $customer->username = $secret['name'];
                                $customer->password_pptp = $secret['password']; // Assuming plain text password from MikroTik
                                $customer->setPasswordAttribute($secret['password']); // Assuming plain text password from MikroTik
                                $customer->service_name = $secret['service'] ?? null;

                                // Find or create PPP Profile
                                $pppProfile = PppProfile::firstOrCreate(['profile_name' => $secret['profile']]);
                                $customer->ppp_profile_id = $pppProfile->id;

                                $customer->local_address = $secret['local-address'] ?? null;
                                $customer->remote_address = $secret['remote-address'] ?? null;
                                $customer->is_active = ($secret['disabled'] ?? 'false') === 'false';

                                // Set default values for required fields if not present in MikroTik
                                $customer->full_name = $customer->full_name ?? $secret['name'];
                                $customer->status = $customer->status ?? 'active';

                                $customer->save();
                                $syncedCount++;
                            }
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Customers synced with MikroTik')
                            ->body("{$syncedCount} new customers added to MikroTik.")
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // 'invoices' => InvoicesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'view' => ViewCustomer::route('/{record}'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CustomerStats::class
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        return static::getUrl('view', ['record' => $record]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['username', 'full_name', 'billing_number', 'email'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'ID Pelanggan' => $record->billing_number,
            'Name' => $record->customer_name,
            'E-Mail' => $record->email
        ];
    }
}
