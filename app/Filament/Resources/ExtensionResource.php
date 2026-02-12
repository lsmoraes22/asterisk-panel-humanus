<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtensionResource\Pages;
use App\Models\Extension;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExtensionResource extends Resource
{
    protected static ?string $model = Extension::class;
    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Telefonia';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Ramal')
                    ->schema([
                        auth()->user()->hasRole('super_admin') 
                            ? Forms\Components\Select::make('tenant_id')
                                ->label('Cliente (Tenant)')
                                ->relationship('tenant', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                            : Forms\Components\Hidden::make('tenant_id')
                                ->default(auth()->user()->tenant_id),

                        Forms\Components\TextInput::make('number')
                            ->label('Número do Ramal')
                            ->required()
                            ->numeric()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ex: 1001'),

                        Forms\Components\TextInput::make('display_name')
                            ->label('Nome do Usuário')
                            ->placeholder('Ex: João Silva')
                            ->required(),

                        Forms\Components\TextInput::make('voicemail')
                            ->label('Voicemail (ex: 1001@default)')
                            ->placeholder('Ex: 1001@default')
                            ->helperText('Formato: ramal@contexto'),

                        Forms\Components\TextInput::make('password')
                            ->label('Senha SIP')
                            ->password()
                            ->required(fn ($context) => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->revealable(),
                    ])->columns(2),

                Forms\Components\Section::make('Configurações Avançadas')
                    ->collapsed()
                    ->schema([
                        Forms\Components\Select::make('context')
                            ->label('Contexto de Discagem')
                            ->options([
                                'from-internal' => 'Somente Interno',
                                'local'    => 'Fixo Local',
                                'celular'  => 'Fixo e Celular',
                                'full'     => 'Completo (DDI/DDD)',
                            ])
                            ->default('from-internal')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Ramal')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('display_name')
                    ->label('Nome')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Cliente')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('context')
                    ->label('Contexto')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->label('Filtrar por Cliente'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExtensions::route('/'),
            'create' => Pages\CreateExtension::route('/create'),
            'edit' => Pages\EditExtension::route('/{record}/edit'),
        ];
    }
}
