<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use App\Services\TenantService;
use Filament\Resources\Pages\CreateRecord;
use App\Services\AsteriskTenantService;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $service = app(TenantService::class);
        $data['code'] = $service->generateNextCode();

        return $data;
    }

}
