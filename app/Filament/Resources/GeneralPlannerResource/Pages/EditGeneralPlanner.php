<?php

namespace App\Filament\Resources\GeneralPlannerResource\Pages;

use App\Filament\Resources\GeneralPlannerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeneralPlanner extends EditRecord
{
    protected static string $resource = GeneralPlannerResource::class;

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
