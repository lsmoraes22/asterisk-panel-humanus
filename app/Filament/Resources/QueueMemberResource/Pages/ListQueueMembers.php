<?php

namespace App\Filament\Resources\QueueMemberResource\Pages;

use App\Filament\Resources\QueueMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQueueMembers extends ListRecords
{
    protected static string $resource = QueueMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

