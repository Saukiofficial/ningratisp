<?php

namespace App\Filament\Admin\Resources\Complaints\Pages;

use App\Filament\Admin\Resources\Complaints\ComplaintResource;
use Filament\Resources\Pages\CreateRecord;

class CreateComplaint extends CreateRecord
{
    protected static string $resource = ComplaintResource::class;
}
