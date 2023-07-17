<?php

namespace App\Filament\Resources\SpecificPlannerResource\Pages;

use App\Filament\Resources\SpecificPlannerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpecificPlanners extends ListRecords
{
    protected static string $resource = SpecificPlannerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
