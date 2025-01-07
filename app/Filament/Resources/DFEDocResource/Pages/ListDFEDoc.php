<?php

namespace App\Filament\Resources\DFEDocResource\Pages;

use App\Filament\Resources\DFEDocResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDFEDoc extends ListRecords
{
    protected static string $resource = DFEDocResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
