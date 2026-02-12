<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PjsipAorResource\Pages;
use App\Filament\Resources\PjsipAorResource\RelationManagers;
use App\Models\PjsipAor;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PjsipAorResource extends Resource
{
    protected static ?string $model = PjsipAor::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
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
            Forms\Components\Hidden::make('tenant_id')
            ->default(fn () => auth()->user()->tenant_id);
        }
        return $form->schema([
            $tenant_input,

            Forms\Components\TextInput::make('name')
                ->label('Nome')
                ->required(),
            Forms\Components\TextInput::make('id')
                ->label('ID/Ramal do AOR')
                ->required()
                ->unique(ignoreRecord: true) // O Filament verifica se já existe no banco
                ->validationMessages([
                    'unique' => 'Este ID de AOR já existe no sistema.'
                ])
                ->maxLength(255),
            Forms\Components\TextInput::make('max_contacts')
                ->label('Máximo Contatos')
		->numeric()
                ->required(),
	]);
    }

    public static function table(Table $table): Table
    {
        $is_super_admin = auth()->user()->hasRole('super_admin');
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->sortable()
                    ->toggleable(!$is_super_admin),
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_contacts')
                    ->sortable(),
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
            'index' => Pages\ListPjsipAors::route('/'),
            'create' => Pages\CreatePjsipAor::route('/create'),
            'edit' => Pages\EditPjsipAor::route('/{record}/edit'),
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
