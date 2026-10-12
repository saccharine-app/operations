<?php

namespace Saccharine\Operations\Filament\Resources\IntakeQueueResource\Pages;

use Saccharine\Operations\Filament\Resources\IntakeQueueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIntakeQueues extends ListRecords
{
    protected static string $resource = IntakeQueueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
