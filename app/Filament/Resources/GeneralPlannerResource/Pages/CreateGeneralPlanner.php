<?php

namespace App\Filament\Resources\GeneralPlannerResource\Pages;

use App\Filament\Resources\GeneralPlannerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGeneralPlanner extends CreateRecord
{
    protected static string $resource = GeneralPlannerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
