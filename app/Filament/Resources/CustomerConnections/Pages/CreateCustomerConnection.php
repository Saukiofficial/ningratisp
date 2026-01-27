<?php

namespace App\Filament\Resources\CustomerConnections\Pages;

use App\Filament\Resources\CustomerConnections\CustomerConnectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerConnection extends CreateRecord
{
    protected static string $resource = CustomerConnectionResource::class;
}
