<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PjsipEndpointResource\Pages;
use App\Filament\Resources\PjsipEndpointResource\RelationManagers;
use App\Models\PjsipEndpoint;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PjsipEndpointResource extends Resource
{
    protected static ?string $model = PjsipEndpoint::class;
    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Telefonia';
    protected static ?string $label = 'Endpoint / Ramal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        // Aba de Configurações Básicas (Ramais)
                        Forms\Components\Tabs\Tab::make('Geral')
                            ->icon('heroicon-m-user')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('id')
                                        ->label('Número do Ramal / ID')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->disabled(fn ($context) => $context === 'edit') // Evita quebra de FK no Asterisk
                                        ->placeholder('Ex: 1000'),
                                        
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nome de Exibição')
                                        ->required()
                                        ->placeholder('Ex: Financeiro - João'),

                                    Forms\Components\Select::make('tenant_id')
                                        ->relationship('tenant', 'name')
                                        ->required()
                                        ->searchable()
                                        ->preload(),

                                    Forms\Components\TextInput::make('mailboxes')
                                        ->label('Caixa Postal')
                                        ->placeholder('Ex: 1000@default'),
                                ]),
                            ]),

                        // Aba para Troncos / Linhas IP
                        Forms\Components\Tabs\Tab::make('Autenticação por IP')
                            ->icon('heroicon-m-globe-alt')
                            ->schema([
                                Forms\Components\Section::make('Identificação de Host (Troncos)')
                                    ->description('Preencha apenas se este endpoint for uma linha de operadora autenticada por IP.')
                                    ->schema([
                                        Forms\Components\TextInput::make('match')
                                            ->label('IP ou Rede da Operadora')
                                            ->placeholder('Ex: 200.155.10.1 ou 200.155.10.0/24')
                                            ->helperText('O Asterisk identificará chamadas deste IP como pertencentes a este endpoint.'),
                                            
                                        Forms\Components\Hidden::make('endpoint')
                                            ->dehydrated(true)
                                            ->default(fn ($get) => $get('id')),
                                    ]),
                            ]),

                        // Aba de Rede e Codecs
                        Forms\Components\Tabs\Tab::make('Avançado')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Select::make('transport')
                                        ->options([
                                            'transport-udp' => 'UDP',
                                            'transport-tcp' => 'TCP',
                                            'transport-tls' => 'TLS',
                                        ])
                                        ->default('transport-udp'),

                                    Forms\Components\TextInput::make('context')
                                        ->label('Contexto Personalizado')
                                        ->placeholder('Deixe vazio para usar o padrão do Tenant'),

                                    Forms\Components\TextInput::make('allow')
                                        ->label('Codecs Permitidos')
                                        ->default('opus,alaw,ulaw,g722')
                                        ->placeholder('opus,alaw,ulaw'),
                                ]),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Ramal')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->badge(),

                Tables\Columns\TextColumn::make('match')
                    ->label('IP Trunk')
                    ->placeholder('Apenas Senha')
                    ->color('warning')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('context')
                    ->label('Contexto')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->label('Filtrar por Tenant'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPjsipEndpoints::route('/'),
            'create' => Pages\CreatePjsipEndpoint::route('/create'),
            'edit' => Pages\EditPjsipEndpoint::route('/{record}/edit'),
        ];
    }
}

/*
namespace App\Filament\Resources;

use App\Filament\Resources\PjsipEndpointResource\Pages;
use App\Filament\Resources\PjsipEndpointResource\RelationManagers;
use App\Models\PjsipEndpoint;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class PjsipEndpointResource extends Resource
{
    protected static ?string $model = PjsipEndpoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Asterisk';

    public static function form(Form $form): Form
    {
        $is_super_admin = auth()->user()->hasRole('super_admin');
        if($is_super_admin){
            $tenant_input = Forms\Components\Select::make('tenant_id')
                ->label('Tenant')
                ->options(fn () => Tenant::orderBy('name')->pluck('name', 'id'))
                ->required();
        } else {
            $tenant_input = Forms\Components\Hidden::make('tenant_id')
                ->default(fn () => auth()->user()->tenant_id);
        }
        return $form
            ->schema([
                $tenant_input,

                Forms\Components\TextInput::make('id')
                    ->label('ID/Ramal do Endpoint')
                    ->required()
                    ->unique(ignoreRecord: true) // O Filament verifica se já existe no banco
                    ->validationMessages([
                        'unique' => 'Este ID de endpoint já existe no sistema.',
                    ])
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nome / Apelido')
                    ->maxLength(255),

                Forms\Components\TextInput::make('auth')
                    ->maxLength(255),

                Forms\Components\TextInput::make('mailboxes')
                    ->label('Mailboxes (separadas por vírgula)')
                    ->placeholder('ex: 1001@default')
                    ->helperText('Formato: ramal@contexto')
                    ->maxLength(255),

                Forms\Components\TextInput::make('aor')
                    ->maxLength(255),

                Forms\Components\TextInput::make('context')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('transport')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('allow')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        $is_super_admin = auth()->user()->hasRole('super_admin');
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant_id')->sortable($is_super_admin)
                    ->toggleable(isToggledHiddenByDefault: !$is_super_admin),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('auth')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('mailboxes')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('aor')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('context')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('transport')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('allow')->searchable()->sortable(),
            ])
            ->filters([ ])
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
            'index' => Pages\ListPjsipEndpoints::route('/'),
            'create' => Pages\CreatePjsipEndpoint::route('/create'),
            'edit' => Pages\EditPjsipEndpoint::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        $query = parent::getEloquentQuery();

        if (!$user->hasRole('super_admin')) {
            $query->where('tenant_id', $user->tenant_id);
        }

        return $query;
    }
}
/**/