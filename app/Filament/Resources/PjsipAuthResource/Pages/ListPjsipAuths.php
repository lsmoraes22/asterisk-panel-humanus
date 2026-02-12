<?php

namespace App\Filament\Resources\PjsipAuthResource\Pages;

use App\Filament\Resources\PjsipAuthResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPjsipAuths extends ListRecords
{
    protected static string $resource = PjsipAuthResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
