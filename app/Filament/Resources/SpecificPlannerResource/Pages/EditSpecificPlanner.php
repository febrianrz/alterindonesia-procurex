<?php

namespace App\Filament\Resources\SpecificPlannerResource\Pages;

use App\Filament\Resources\SpecificPlannerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecificPlanner extends EditRecord
{
    protected static string $resource = SpecificPlannerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
