<?php

namespace App\Filament\Resources\DidNumberResource\Pages;

use App\Filament\Resources\DidNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDidNumbers extends ListRecords
{
    protected static string $resource = DidNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
