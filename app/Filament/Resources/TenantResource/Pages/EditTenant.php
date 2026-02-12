<?php

namespace App\Filament\Resources\TenantResource\Pages;

use App\Filament\Resources\TenantResource;
use Filament\Resources\Pages\EditRecord;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Proteger o code caso venha no POST
        unset($data['code']);
        return $data;
    }
    protected function afterEdit(): void
    {
	$this->record;
    }

}
