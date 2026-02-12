<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoicemailBoxResource\Pages;
use App\Filament\Resources\VoicemailBoxResource\RelationManagers;
use App\Models\VoicemailBox;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoicemailBoxResource extends Resource
{
    protected static ?string $model = VoicemailBox::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';
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

                Forms\Components\TextInput::make('mailbox')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->required()
		    ->password()
                    ->maxLength(255),

                Forms\Components\TextInput::make('name')
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
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
                Tables\Columns\TextColumn::make('mailbox')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
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
            'index' => Pages\ListVoicemailBoxes::route('/'),
            'create' => Pages\CreateVoicemailBox::route('/create'),
            'edit' => Pages\EditVoicemailBox::route('/{record}/edit'),
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
