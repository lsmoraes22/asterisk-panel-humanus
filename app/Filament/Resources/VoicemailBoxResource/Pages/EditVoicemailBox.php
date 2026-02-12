<?php

namespace App\Filament\Resources\VoicemailBoxResource\Pages;

use App\Filament\Resources\VoicemailBoxResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVoicemailBox extends EditRecord
{
    protected static string $resource = VoicemailBoxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
