<?php

namespace App\Filament\Resources\SpecialDateResource\Pages;

use App\Filament\Resources\SpecialDateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecialDate extends EditRecord
{
    protected static string $resource = SpecialDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
