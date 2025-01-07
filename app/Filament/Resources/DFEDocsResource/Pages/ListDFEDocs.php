<?php

namespace App\Filament\Resources\DFEDocsResource\Pages;

use App\Filament\Resources\DFEDocsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDFEDocs extends ListRecords
{
    protected static string $resource = DFEDocsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
