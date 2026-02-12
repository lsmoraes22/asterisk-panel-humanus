<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InviteResource\Pages;
use App\Filament\Resources\InviteResource\RelationManagers;
use App\Models\Invite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InviteResource extends Resource
{
    protected static ?string $model = Invite::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'Segurança';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Forms\Components\TextInput::make('label')
                    ->placeholder('Ex: Home Office Lucas')
                    ->required(),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Expira em')
                    ->default(now()->addDays(7)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')->label('Tenant'),
                Tables\Columns\TextColumn::make('label')->searchable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Liberado')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->placeholder('Aguardando clique...'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Validade')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('token')
                    ->label('Link de Acesso')
                    ->formatStateUsing(fn ($record) => url("/liberar-acesso/{$record->token}"))
                    ->copyable() // Aqui funciona perfeitamente!
                    ->copyMessage('Link de acesso copiado!')
                    ->icon('heroicon-o-clipboard')
                    ->color('primary')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\Action::make('copy_link')
                    ->label('Link')
                    ->icon('heroicon-o-clipboard')
                    ->color('info')->extraAttributes(fn ($record) => [
                        'onclick' => "window.navigator.clipboard.writeText('" . url("/liberar-acesso/{$record->token}") . "'); 
                                    new FilamentNotification().title('Link copiado!').success().send();"
                    ]),
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
            'index' => Pages\ListInvites::route('/'),
            'create' => Pages\CreateInvite::route('/create'),
            'edit' => Pages\EditInvite::route('/{record}/edit'),
        ];
    }
}
