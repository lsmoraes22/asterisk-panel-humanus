<?php

namespace App\Filament\Resources\PjsipAuthResource\Pages;

use App\Filament\Resources\PjsipAuthResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPjsipAuth extends EditRecord
{
    protected static string $resource = PjsipAuthResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
