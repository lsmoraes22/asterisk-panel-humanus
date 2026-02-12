<?php

namespace App\Filament\Resources\DialplanResource\Pages;

use App\Filament\Resources\DialplanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDialplans extends ListRecords
{
    protected static string $resource = DialplanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
