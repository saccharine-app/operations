<?php

namespace Saccharine\Operations\Filament\Resources\IntakeQueueResource\Pages;

use Saccharine\Operations\Filament\Resources\IntakeQueueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIntakeQueue extends EditRecord
{
    protected static string $resource = IntakeQueueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
