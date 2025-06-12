<?php

namespace App\Filament\Resources\SpecialDateResource\Pages;

use App\Filament\Resources\SpecialDateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpecialDates extends ListRecords
{
    protected static string $resource = SpecialDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
