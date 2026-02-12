<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QueueMemberResource\Pages;
use App\Models\Queue;
use App\Models\Tenant;
use App\Models\PjsipEndpoint;
use App\Models\QueueMember;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class QueueMemberResource extends Resource
{
    protected static ?string $model = QueueMember::class;

    protected static ?string $navigationGroup = 'Asterisk';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?int $navigationSort = 41;
    protected static ?string $modelLabel = 'Membro de Fila';
    protected static ?string $pluralModelLabel = 'Membros de Filas';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Group::make([

                // Seletor de Tenant (somente super_admin vê)
                Forms\Components\Select::make('tenant_id')
                    ->label('Tenant')
                    ->relationship('tenant', 'code')
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->default(fn () => auth()->user()->tenant_id)
                    ->required(),

                // Hidden para usuários normais
                Forms\Components\Hidden::make('tenant_id')
                    ->default(fn () => auth()->user()->tenant_id)
                    ->visible(fn () => !auth()->user()->hasRole('super_admin')),

                Forms\Components\Select::make('queue_id')
                    ->label('Fila')
                    ->relationship('queue', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('endpoint_id')
                    ->label('Endpoint')
                    ->options(
                        PjsipEndpoint::query()
                            ->orderBy('id')
                            ->pluck('id', 'id') // Valor: id | Texto exibido: id
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('penalty')
                    ->numeric()
                    ->label('Penalty')
                    ->default(0),

                Forms\Components\Toggle::make('paused')
                    ->label('Pausado')
                    ->onColor('danger')
                    ->offColor('success')
                    ->default(false)
                    ->dehydrateStateUsing(fn ($state) => $state ? 'yes' : 'no')
                    ->formatStateUsing(fn ($state) => $state === 'yes'),

                Forms\Components\TextInput::make('state_interface')
                    ->label('State Interface')
                    ->placeholder('Ex: PJSIP/1001')
                    ->maxLength(100),

                Forms\Components\TextInput::make('membername')
                    ->label('Nome do Membro')
                    ->maxLength(100)
                    ->nullable(),

            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('tenant.code')
                    ->label('Tenant')
                    ->toggleable()
                    ->sortable()
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('super_admin')),

                Tables\Columns\TextColumn::make('queue.name')
                    ->label('Fila')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('endpoint_id')
                    ->label('Endpoint')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('penalty')
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('paused')
                    ->label('Pausado')
                    ->trueIcon('heroicon-o-pause')
                    ->falseIcon('heroicon-o-check')
                    ->trueColor('danger')
                    ->falseColor('success'),
                    //->formatStateUsing(fn ($state) => $state === 'yes'),  //erro

                Tables\Columns\TextColumn::make('state_interface')
                    ->label('Interface')
                    ->searchable(),

                Tables\Columns\TextColumn::make('membername')
                    ->label('Nome')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(),

            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQueueMembers::route('/'),
            'create' => Pages\CreateQueueMember::route('/create'),
            'edit' => Pages\EditQueueMember::route('/{record}/edit'),
        ];
    }
}
