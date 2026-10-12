<?php

namespace Saccharine\Operations\Filament\Resources\CaseFileResource\Pages;

use Saccharine\Operations\Filament\Resources\CaseFileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCaseFiles extends ListRecords
{
    protected static string $resource = CaseFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
