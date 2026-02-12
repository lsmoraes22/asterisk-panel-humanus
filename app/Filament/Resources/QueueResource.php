<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QueueResource\Pages;
use App\Filament\Resources\QueueResource\RelationManagers\QueueMembersRelationManager;
use App\Models\Queue;
use App\Models\QueueRule;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QueueResource extends Resource
{
    protected static ?string $model = Queue::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationGroup = 'Asterisk';

    public static function form(Form $form): Form
    {
        $is_super_admin = auth()->user()->hasRole('super_admin');

        $tenantField = $is_super_admin
            ? Forms\Components\Select::make('tenant_id')
                ->label('Tenant')
                ->options(fn () => Tenant::orderBy('name')->pluck('name', 'id'))
                ->required()
            : Forms\Components\Hidden::make('tenant_id')
                ->default(fn () => auth()->user()->tenant_id);

        return $form
            ->schema([
                Forms\Components\Tabs::make('QueueTabs')
                    ->tabs([
                        /*
                        |--------------------------------------------------------------------------
                        | ABA 1 – CONFIGURAÇÕES BÁSICAS
                        |--------------------------------------------------------------------------
                        */
                        Forms\Components\Tabs\Tab::make('Básico')
                            ->schema([
                                $tenantField,

                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('strategy')
                                    ->label('Estratégia de Distribuição')
                                    ->options([
                                        'ringall' => 'Ring All',
                                        'leastrecent' => 'Least Recent',
                                        'fewestcalls' => 'Fewest Calls',
                                        'random' => 'Random',
                                        'rrmemory' => 'Round Robin with Memory',
                                        'linear' => 'Linear',
                                        'wrandom' => 'Weighted Random',
                                        'wrrmemory' => 'Weighted Round Robin with Memory',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('timeout')
                                    ->required()
                                    ->numeric(),

                                Forms\Components\TextInput::make('musicclass')
                                    ->required()
                                    ->default('default'),

                                Forms\Components\TextInput::make('retry')
                                    ->numeric()
                                    ->default(5),

                                Forms\Components\TextInput::make('wrapuptime')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('maxlen')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('weight')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        /*
                        |--------------------------------------------------------------------------
                        | ABA 2 – COMPORTAMENTO DA FILA
                        |--------------------------------------------------------------------------
                        */
                        Forms\Components\Tabs\Tab::make('Comportamento')
                            ->schema([
                                Forms\Components\Toggle::make('joinempty')
                                    ->label('Join empty')
                                    ->default(false)
                                    ->inline(false),

                                Forms\Components\Toggle::make('leavewhenempty')
                                    ->label('Leave when empty')
				    ->default(true)
                                    ->inline(false),

                                Forms\Components\Toggle::make('ringinuse')
                                    ->inline(false),

                                Forms\Components\Toggle::make('timeoutrestart')
				    ->default(true)
                                    ->inline(false),
                            ]),

                        /*
                        |--------------------------------------------------------------------------
                        | ABA 3 – ANÚNCIOS
                        |--------------------------------------------------------------------------
                        */
                        Forms\Components\Tabs\Tab::make('Anúncios')
                            ->schema([
                                Forms\Components\Toggle::make('announce_holdtime')
                				    ->default(true)
                                    ->inline(false),

                                Forms\Components\Toggle::make('announce_position')
				                    ->default(true)
                                    ->inline(false),
                                Forms\Components\TextInput::make('announce_frequency')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('announce_override')
                                    ->default(null),

                                Forms\Components\TextInput::make('announce_round_seconds')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        /*
                        |--------------------------------------------------------------------------
                        | ABA 4 – VARIÁVEIS / FLAGS
                        |--------------------------------------------------------------------------
                        */
                        Forms\Components\Tabs\Tab::make('Variáveis')
                            ->schema([
                                Forms\Components\Toggle::make('setinterfacevar')
				    ->default(true)
                                    ->inline(false),

                                Forms\Components\Toggle::make('setqueuevar')
				    ->default(true)
                                    ->inline(false),

                                Forms\Components\Toggle::make('setqueueentryvar')
				    ->default(true)
                                    ->inline(false),

                                Forms\Components\Toggle::make('reportholdtime')
                                    ->inline(false),
                            ]),

                        /*
                        |--------------------------------------------------------------------------
                        | ABA 5 – AUTOPAUSE
                        |--------------------------------------------------------------------------
                        */
                        Forms\Components\Tabs\Tab::make('Autopause')
                            ->schema([
                                Forms\Components\Toggle::make('autopause')
                                    ->inline(false),

                                Forms\Components\TextInput::make('autopausedelay')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\Toggle::make('autopausebusy')
                                    ->inline(false),

                                Forms\Components\Toggle::make('autopauseunavail')
                                    ->inline(false),
                            ]),

                        /*
                        |--------------------------------------------------------------------------
                        | ABA 6 – PENALIDADES
                        |--------------------------------------------------------------------------
                        */
                        Forms\Components\Tabs\Tab::make('Penalidades')
                            ->schema([
                                Forms\Components\TextInput::make('penaltymemberslimit')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('penaltytimeout')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('penaltytimerepeat')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        /*
                        |--------------------------------------------------------------------------
                        | ABA 7 – AVANÇADO
                        |--------------------------------------------------------------------------
                        */
                        Forms\Components\Tabs\Tab::make('Avançado')
                            ->schema([
                                Forms\Components\TextInput::make('context')
                                    ->default(null),

                                Forms\Components\TextInput::make('monitor_format')
                                    ->default(null),

                                Forms\Components\TextInput::make('memberdelay')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\Select::make('queue_rule_id')
                                    ->label('Queue Rule')
                                    ->options(fn () => QueueRule::orderBy('name')->pluck('name', 'id'))
                                    ->searchable()
                                    ->nullable(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $is_super_admin = auth()->user()->hasRole('super_admin');

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant_id')
                    ->label('Tenant')
                    ->toggleable(isToggledHiddenByDefault: !$is_super_admin)
                    ->sortable($is_super_admin),

                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('strategy')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('musicclass'),
                Tables\Columns\TextColumn::make('timeout'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
    	return [
            QueueMembersRelationManager::class
    	];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQueues::route('/'),
            'create' => Pages\CreateQueue::route('/create'),
            'edit' => Pages\EditQueue::route('/{record}/edit'),
        ];
    }
}
