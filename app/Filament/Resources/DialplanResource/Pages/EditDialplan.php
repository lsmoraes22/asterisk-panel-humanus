<?php

namespace App\Filament\Resources\DialplanResource\Pages;

use App\Filament\Resources\DialplanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDialplan extends EditRecord
{
    protected static string $resource = DialplanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
