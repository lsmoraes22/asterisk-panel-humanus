<?php

namespace App\Filament\Resources\DidNumberResource\Pages;

use App\Filament\Resources\DidNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDidNumber extends EditRecord
{
    protected static string $resource = DidNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
