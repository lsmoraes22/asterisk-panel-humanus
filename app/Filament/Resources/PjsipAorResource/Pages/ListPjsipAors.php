<?php

namespace App\Filament\Resources\PjsipAorResource\Pages;

use App\Filament\Resources\PjsipAorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPjsipAors extends ListRecords
{
    protected static string $resource = PjsipAorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
