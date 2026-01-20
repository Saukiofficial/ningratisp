<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Packages;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected ?bool $hasDatabaseTransactions = true;

    protected static string $resource = CustomerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $package = Packages::find($data['package_id']);
        $data['ppp_profile_id'] = $package->ppp_profile_id;
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->customerPackages()->create([
            'package_id' => $this->data['package_id'],
            'start_date' => now()
        ]);
    }
}
