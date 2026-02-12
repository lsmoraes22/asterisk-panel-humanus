<?php

namespace App\Filament\Resources\QueueMemberResource\Pages;

use App\Filament\Resources\QueueMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQueueMember extends EditRecord
{
    protected static string $resource = QueueMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

