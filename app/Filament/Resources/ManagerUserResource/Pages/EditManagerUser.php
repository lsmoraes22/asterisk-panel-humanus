<?php

namespace App\Filament\Resources\ManagerUserResource\Pages;

use App\Filament\Resources\ManagerUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManagerUser extends EditRecord
{
    protected static string $resource = ManagerUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
