<?php

namespace App\Filament\Resources\SpecificPlannerResource\Pages;

use App\Filament\Resources\SpecificPlannerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpecificPlanner extends CreateRecord
{
    protected static string $resource = SpecificPlannerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
