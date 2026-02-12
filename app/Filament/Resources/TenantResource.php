<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Tenants';
    protected static ?string $pluralModelLabel = 'Tenants';
    protected static ?string $modelLabel = 'Tenant';
    protected static ?string $navigationGroup = 'Configuração';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->disabled()
                    ->label('Código')
                    ->hint('Gerado automaticamente (tenant-0001)')
                    ->dehydrated(false), // evita salvar sem querer

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nome do Cliente')
                    ->maxLength(255),

                Forms\Components\TextInput::make('domain')
                    ->label('Domínio SIP Público')
                    ->maxLength(255),

                Forms\Components\TextInput::make('external_signaling_address')
                    ->label('Endereço Externo SIP (Sinalização)')
                    ->ip()
                    ->maxLength(255),

                Forms\Components\TextInput::make('external_media_address')
                    ->label('Endereço Externo RTP (Mídia)')
                    ->ip()
                    ->maxLength(255),

                Forms\Components\TextInput::make('local_net')
                    ->label('Rede Interna (CIDR)')
                    ->placeholder('ex: 192.168.0.0/24')
                    ->maxLength(255),

                Forms\Components\TextInput::make('max_endpoints')
                    ->numeric()
                    ->minValue(1)
                    ->default(20)
                    ->label('Máx. Endpoints'),

                Forms\Components\TextInput::make('max_queues')
                    ->numeric()
                    ->minValue(1)
                    ->default(5)
                    ->label('Máx. Filas'),

                Forms\Components\TextInput::make('max_channels')
                    ->numeric()
                    ->minValue(1)
                    ->default(50)
                    ->label('Máx. Canais simultâneos'),

                Forms\Components\Select::make('timezone')
                    ->label('Fuso Horário')
                    ->options(self::timezones())
                    ->searchable()
                    ->default('America/Sao_Paulo'),

                Forms\Components\Toggle::make('active')
                    ->label('Ativo')
                    ->default(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('domain')
                    ->label('Domínio')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('active')
                    ->label('Ativo')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Ativo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }

    /**
     * Lista de timezones para o select
     */
    public static function timezones(): array
    {
        $timezones = \DateTimeZone::listIdentifiers();
        return array_combine($timezones, $timezones);
    }
}
