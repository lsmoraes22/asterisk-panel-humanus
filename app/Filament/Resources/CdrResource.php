<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CdrResource\Pages;
use App\Filament\Resources\CdrResource\RelationManagers;
use App\Models\Cdr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CdrResource extends Resource
{
    protected static ?string $model = Cdr::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone-arrow-up-right';
    protected static ?string $navigationGroup = 'Asterisk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('calldate')->searchable(),
                Tables\Columns\TextColumn::make('src'),
                Tables\Columns\TextColumn::make('dst'),
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('billsec'),
                Tables\Columns\TextColumn::make('disposition'),
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
            'index' => Pages\ListCdrs::route('/'),
            'create' => Pages\CreateCdr::route('/create'),
            'edit' => Pages\EditCdr::route('/{record}/edit'),
        ];
    }
}
