<?php

namespace App\Filament\Resources\AsteriskHttpResource\Pages;

use App\Filament\Resources\AsteriskHttpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsteriskHttps extends ListRecords
{
    protected static string $resource = AsteriskHttpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
