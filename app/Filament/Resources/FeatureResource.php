<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeatureResource\Pages;
use App\Models\Feature;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
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
        return $form->schema([
	    $tenant_input,

            Forms\Components\TextInput::make('name')
                ->label('Nome')
                ->required(),

            Forms\Components\TextInput::make('code')
                ->label('Código')
                ->required(),

            Forms\Components\Toggle::make('enable')
                ->label('Ativo'),
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

                Tables\Columns\TextColumn::make('code')
                    ->sortable(),

                Tables\Columns\IconColumn::make('enabled')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFeatures::route('/'),
            'create' => Pages\CreateFeature::route('/create'),
            'edit'   => Pages\EditFeature::route('/{record}/edit'),
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
