<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DidNumberResource\Pages;
use App\Filament\Resources\DidNumberResource\RelationManagers;
use App\Models\DidNumber;
use App\Models\Tenant;
use App\Models\PjsipEndpoint;
use App\Models\Queue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DidNumberResource extends Resource
{
    protected static ?string $model = DidNumber::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';
    protected static ?string $navigationGroup = 'Asterisk';

public static function form(Form $form): Form
{
    $is_super_admin = auth()->user()->hasRole('super_admin');

    return $form
        ->schema([
            Forms\Components\Section::make('Identificação do DID')
                ->schema([
                    $is_super_admin 
                        ? Forms\Components\Select::make('tenant_id')
                            ->label('Tenant')
                            ->options(Tenant::pluck('name', 'id'))
                            ->required()
                            ->live() // Importante para atualizar os campos dependentes
                        : Forms\Components\Hidden::make('tenant_id')
                            ->default(auth()->user()->tenant_id),

                    Forms\Components\TextInput::make('number')
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->label('Número (DID):'),

                    Forms\Components\TextInput::make('description')
                        ->label('Descrição'),
                ])->columns(2),

            Forms\Components\Section::make('Roteamento de Entrada')
                ->schema([
                    Forms\Components\Select::make('destination_type')
                        ->label('Tipo de Destino')
                        ->options([
                            'endpoint' => 'Ramal',
                            'queue' => 'Fila',
                            'ivr' => 'URA (Custom)',
                            'custom' => 'Personalizado',
                        ])
                        ->default('endpoint')
                        ->live() // Faz o campo de destino reagir à mudança
                        ->required(),

                    // Campo Dinâmico de Destino
                    Forms\Components\Select::make('destination')
                        ->label('Destino')
                        ->required()
                        ->options(function (Forms\Get $get) {
                            $tenantId = $get('tenant_id');
                            $type = $get('destination_type');

                            if (!$tenantId) return [];

                            if ($type === 'endpoint') {
                                // Busca ramais filtrados pelo Tenant
                                return PjsipEndpoint::where('tenant_id', $tenantId)
                                    ->pluck('extension', 'extension'); 
                            }

                            if ($type === 'queue') {
                                // Busca filas filtradas pelo Tenant
                                return Queue::where('tenant_id', $tenantId)
                                    ->pluck('name', 'number'); 
                            }

                            return [];
                        })
                        // Habilita entrada de texto livre se for Custom
                        ->addable(fn (Forms\Get $get) => in_array($get('destination_type'), ['ivr', 'custom']))
                        ->searchable(),

                    Forms\Components\Toggle::make('active')
                        ->label('Ativo')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('tenant_id')->label('Tenant Id')->sortable(),
                Tables\Columns\TextColumn::make('number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('destination_type')->label('Tipo destino')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')->label('Descrição')->toggleable()->default(false),
                Tables\Columns\IconColumn::make('active')->label('Ativo')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDidNumbers::route('/'),
            'create' => Pages\CreateDidNumber::route('/create'),
            'edit' => Pages\EditDidNumber::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
    
        // Se não for super_admin, filtra apenas os DIDs do tenant do usuário logado
        if (!auth()->user()->hasRole('super_admin')) {
            return $query->where('tenant_id', auth()->user()->tenant_id);
        }

        return $query;
    }
}
