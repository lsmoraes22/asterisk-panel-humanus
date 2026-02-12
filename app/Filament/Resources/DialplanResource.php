<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DialplanResource\Pages;
use App\Filament\Resources\DialplanResource\RelationManagers;
use App\Models\Dialplan;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DialplanResource extends Resource
{
    protected static ?string $model = Dialplan::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';
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

                Forms\Components\TextInput::make('context')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('exten')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('priority')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('application')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('app_data')
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('tenant_id')->sortable(),
                Tables\Columns\TextColumn::make('context')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('exten')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('priority')->sortable(),
                Tables\Columns\TextColumn::make('application')->sortable(),
                Tables\Columns\TextColumn::make('app_data'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListDialplans::route('/'),
            'create' => Pages\CreateDialplan::route('/create'),
            'edit' => Pages\EditDialplan::route('/{record}/edit'),
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
