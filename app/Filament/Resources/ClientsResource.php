<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientsResource\Pages;
use App\Filament\Resources\ClientsResource\RelationManagers;
use App\Models\AuthClient;
use App\Models\Clients;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ClientsResource extends Resource
{
    protected static ?string $model = AuthClient::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = "User Management";

    protected static ?string $navigationLabel = "Client";



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('secret')
                        ->required()
                        ->disabled(true)
                        ->default(Str::random(60)),
                    Forms\Components\Toggle::make('is_active')
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('secret')->sortable()->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')->sortable()->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClients::route('/create'),
            'edit' => Pages\EditClients::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
