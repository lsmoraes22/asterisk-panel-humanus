<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QueueRuleResource\Pages;
use App\Models\QueueRule;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QueueRuleResource extends Resource
{
    protected static ?string $model = QueueRule::class;
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'Asterisk';
    protected static ?string $label = 'Regra de Fila';
    protected static ?string $pluralLabel = 'Regras de Fila';

    /** -----------------------------
     *  QUERY MULTI-TENANT AUTOMÁTICA
     * ----------------------------- */
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        // Super Admin vê tudo
        if ($user->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }

        // Usuário comum vê somente seu tenant
        return parent::getEloquentQuery()->where('tenant_id', $user->tenant_id);
    }

    /** -----------------------------
     *  FORMULÁRIO
     * ----------------------------- */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(12)->schema([

                /** SUPER ADMIN PODE ESCOLHER O TENANT */
                Forms\Components\Select::make('tenant_id')
                    ->relationship('tenant', 'code')
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->required(),

                /** USUÁRIO COMUM SALVA AUTOMATICAMENTE O TENANT */
                Forms\Components\Hidden::make('tenant_id')
                    ->default(fn () => auth()->user()->tenant_id)
                    ->visible(fn () => !auth()->user()->hasRole('super_admin')),

                Forms\Components\TextInput::make('name')
                    ->label('Nome da Regra')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->columnSpan(6),

                Forms\Components\Textarea::make('description')
                    ->label('Descrição (opcional)')
                    ->columnSpan(6),

                /** STEPS DA REGRA (JSON) */
                Repeater::make('steps')
                    ->label('Etapas da Regra')
                    ->schema([
                        Forms\Components\TextInput::make('announce_frequency')
                            ->label('announce_frequency')
                            ->numeric()
                            ->default(15),

                        Forms\Components\TextInput::make('timeout')
                            ->label('timeout')
                            ->numeric()
                            ->default(30),

                        Forms\Components\TextInput::make('retry')
                            ->label('retry')
                            ->numeric()
                            ->default(5),
                    ])
                    ->columns(3)
                    ->collapsed() // inicia fechado
                    ->addActionLabel('Adicionar Step')
                    ->defaultItems(1)
                    ->columnSpan(12),
            ]),
        ]);
    }

    /** -----------------------------
     *  TABELA
     * ----------------------------- */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant.code')
                    ->label('Tenant')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('steps')
                    ->label('Steps')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state).' steps' : '0')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])

            ->filters([])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
            ])

            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /** -----------------------------
     *  PÁGINAS FILAMENT
     * ----------------------------- */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQueueRules::route('/'),
            'create' => Pages\CreateQueueRule::route('/create'),
            'edit' => Pages\EditQueueRule::route('/{record}/edit'),
        ];
    }
}
