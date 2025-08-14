<?php

namespace App\Filament\Resources\SpecialDateResource\Pages;

use App\Filament\Resources\SpecialDateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpecialDate extends CreateRecord
{
    protected static string $resource = SpecialDateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
