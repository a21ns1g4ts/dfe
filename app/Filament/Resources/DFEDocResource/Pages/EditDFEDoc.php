<?php

namespace App\Filament\Resources\DFEDocResource\Pages;

use App\Filament\Resources\DFEDocResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDFEDoc extends EditRecord
{
    protected static string $resource = DFEDocResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
