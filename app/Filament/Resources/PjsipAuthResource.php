<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PjsipAuthResource\Pages;
use App\Filament\Resources\PjsipAuthResource\RelationManagers;
use App\Models\PjsipAuth;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PjsipAuthResource extends Resource
{
    protected static ?string $model = PjsipAuth::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
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
                    ->label('ID/Ramal do Auth')
                    ->required()
                    ->unique(ignoreRecord: true) // O Filament verifica se já existe no banco
                    ->validationMessages([
                        'unique' => 'Este ID de autenticação já existe no sistema.',
                    ])
                    ->maxLength(255),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('username')
		    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->required()
		    ->password()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
	$is_super_admin = auth()->user()->hasRole('super_admin');
	return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('tenant_id')->sortable($is_super_admin)
		    ->toggleable(isToggledHiddenByDefault: !$is_super_admin),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('username')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPjsipAuths::route('/'),
            'create' => Pages\CreatePjsipAuth::route('/create'),
            'edit' => Pages\EditPjsipAuth::route('/{record}/edit'),
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
