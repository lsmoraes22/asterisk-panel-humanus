<?php

namespace App\Filament\Resources\ManagerUserResource\Pages;

use App\Filament\Resources\ManagerUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManagerUsers extends ListRecords
{
    protected static string $resource = ManagerUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
