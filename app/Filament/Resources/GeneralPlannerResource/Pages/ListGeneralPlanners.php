<?php

namespace App\Filament\Resources\GeneralPlannerResource\Pages;

use App\Filament\Resources\GeneralPlannerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneralPlanners extends ListRecords
{
    protected static string $resource = GeneralPlannerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
