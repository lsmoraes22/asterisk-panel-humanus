<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManagerUserResource\Pages;
use App\Filament\Resources\ManagerUserResource\RelationManagers;
use App\Models\ManagerUser;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManagerUserResource extends Resource
{
    protected static ?string $model = ManagerUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
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
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('secret')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('read')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('write')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('deny')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('permit')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('secret')
                    ->searchable(),
                Tables\Columns\TextColumn::make('read')
                    ->searchable(),
                Tables\Columns\TextColumn::make('write')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deny')
                    ->searchable(),
                Tables\Columns\TextColumn::make('permit')
                    ->searchable(),
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
            'index' => Pages\ListManagerUsers::route('/'),
            'create' => Pages\CreateManagerUser::route('/create'),
            'edit' => Pages\EditManagerUser::route('/{record}/edit'),
        ];
    }
}
