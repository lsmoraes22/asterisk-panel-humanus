<?php

namespace App\Filament\Resources\PjsipEndpointResource\Pages;

use App\Filament\Resources\PjsipEndpointResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPjsipEndpoint extends EditRecord
{
    protected static string $resource = PjsipEndpointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
