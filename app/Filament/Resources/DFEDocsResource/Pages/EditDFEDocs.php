<?php

namespace App\Filament\Resources\DFEDocsResource\Pages;

use App\Filament\Resources\DFEDocsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDFEDocs extends EditRecord
{
    protected static string $resource = DFEDocsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
