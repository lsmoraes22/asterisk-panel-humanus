<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsteriskHttpResource\Pages;
use App\Filament\Resources\AsteriskHttpResource\RelationManagers;
use App\Models\AsteriskHttp;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsteriskHttpResource extends Resource
{
    protected static ?string $model = AsteriskHttp::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
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
                Forms\Components\TextInput::make('bindaddr')->numeric(),
                Forms\Components\TextInput::make('bindport')->numeric(),
                Forms\Components\TextInput::make('prefix'),
                Forms\Components\Toggle::make('sessioncookies'),
                Forms\Components\Toggle::make('enabled'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant_id'),
                Tables\Columns\TextColumn::make('bindaddr')->numeric(),
                Tables\Columns\TextColumn::make('bindport')->numeric(),
                Tables\Columns\TextColumn::make('prefix'),
                Tables\Columns\TextColumn::make('sessioncookies'),
                Tables\Columns\TextColumn::make('enabled')
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
            'index' => Pages\ListAsteriskHttps::route('/'),
            'create' => Pages\CreateAsteriskHttp::route('/create'),
            'edit' => Pages\EditAsteriskHttp::route('/{record}/edit'),
        ];
    }
}
