<?php

namespace App\Filament\Resources\DialplanExtensionResource\Pages;

use App\Filament\Resources\DialplanExtensionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDialplanExtensions extends ListRecords
{
    protected static string $resource = DialplanExtensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
