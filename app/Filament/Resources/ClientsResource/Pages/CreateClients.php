<?php

namespace App\Filament\Resources\ClientsResource\Pages;

use App\Filament\Resources\ClientsResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateClients extends CreateRecord
{
    protected static string $resource = ClientsResource::class;

    protected function beforeCreate(): void
    {

    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
