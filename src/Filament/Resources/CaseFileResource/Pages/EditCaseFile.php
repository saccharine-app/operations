<?php

namespace Saccharine\Operations\Filament\Resources\CaseFileResource\Pages;

use Saccharine\Operations\Filament\Resources\CaseFileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCaseFile extends EditRecord
{
    protected static string $resource = CaseFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
