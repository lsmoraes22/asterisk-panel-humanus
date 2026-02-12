<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DialplanExtensionResource\Pages;
use App\Models\DialplanExtension;
use App\Models\PjsipEndpoint;
use App\Models\Queue;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class DialplanExtensionResource extends Resource
{
    protected static ?string $model = DialplanExtension::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    protected static ?string $navigationGroup = 'Asterisk';

    protected static ?string $navigationLabel = 'Dialplan Extensions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('tenant_id')
                    ->label('Tenant')
                    ->relationship('tenant', 'name')
                    ->required()
                    ->default(fn () => auth()->user()->tenant_id ?? null)
                    ->disabled(fn () => auth()->user()->tenant_id !== null)
                    ->visible(fn () => auth()->user()->tenant_id === null),

                Forms\Components\TextInput::make('extension')
                    ->label('Exten')
                    ->required()
                    ->maxLength(20),

                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'internal' => 'Internal (Endpoint)',
                        'queue'    => 'Queue',
                        'custom'   => 'Custom Dialplan',
                    ])
                    ->required()
                    ->reactive(),

                Forms\Components\Select::make('endpoint_id')
                    ->label('Endpoint')
                    ->options(
                        PjsipEndpoint::query()
                            ->pluck('id', 'id')
                    )
                    ->visible(fn (callable $get) => $get('type') === 'internal')
                    ->required(fn (callable $get) => $get('type') === 'internal'),

                Forms\Components\Select::make('queue_id')
                    ->label('Queue')
                    ->options(
                        Queue::query()
                            ->pluck('name', 'id')
                    )
                    ->visible(fn (callable $get) => $get('type') === 'queue')
                    ->required(fn (callable $get) => $get('type') === 'queue'),

                Forms\Components\Textarea::make('custom_app')
                    ->label('Custom App (ex: Dial(SIP/100))')
                    ->rows(3)
                    ->visible(fn (callable $get) => $get('type') === 'custom')
                    ->required(fn (callable $get) => $get('type') === 'custom'),

                Forms\Components\TextInput::make('priority')
                    ->numeric()
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('extension')
                    ->label('Exten')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->sortable(),

                Tables\Columns\TextColumn::make('endpoint_id')
                    ->label('Endpoint')
                    ->formatStateUsing(fn ($state) => $state ?: '-'),

                Tables\Columns\TextColumn::make('queue.name')
                    ->label('Queue')
                    ->formatStateUsing(fn ($state) => $state ?: '-'),

                Tables\Columns\TextColumn::make('priority')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->visible(fn () => auth()->user()->tenant_id === null),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDialplanExtensions::route('/'),
            'create' => Pages\CreateDialplanExtension::route('/create'),
            'edit'   => Pages\EditDialplanExtension::route('/{record}/edit'),
        ];
    }
}
