<?php

namespace App\Filament\Resources\VegetasiSpeciesResource\Pages;

use App\Filament\Resources\VegetasiSpeciesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVegetasiSpecies extends ListRecords
{
    protected static string $resource = VegetasiSpeciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
