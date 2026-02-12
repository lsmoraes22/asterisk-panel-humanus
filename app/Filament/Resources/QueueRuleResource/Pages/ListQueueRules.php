<?php

namespace App\Filament\Resources\QueueRuleResource\Pages;

use App\Filament\Resources\QueueRuleResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListQueueRules extends ListRecords
{
    protected static string $resource = QueueRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function afterDelete(): void
    {

    }

}
