<?php

namespace App\Filament\Resources\QueueResource\RelationManagers;

use App\Models\PjsipEndpoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class QueueMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    protected static ?string $recordTitleAttribute = 'endpoint_id';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $queue = $this->getOwnerRecord();
        $data['tenant_id'] = $queue->tenant_id;

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('endpoint_id')
                ->label('Endpoint')
                ->options(PjsipEndpoint::pluck('id', 'id'))
                ->required(),

            Forms\Components\TextInput::make('penalty')
                ->numeric()
                ->default(0),

            Forms\Components\Select::make('paused')
                ->options([
                    'yes' => 'Yes',
                    'no'  => 'No',
                ])
                ->default('no'),

            Forms\Components\TextInput::make('membername')
                ->maxLength(255)
                ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('endpoint_id'),
                Tables\Columns\TextColumn::make('penalty'),
                Tables\Columns\TextColumn::make('paused'),
                Tables\Columns\TextColumn::make('membername'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
