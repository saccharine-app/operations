<?php

namespace Saccharine\Operations\Filament\Resources\StaffMemberResource\Pages;

use Saccharine\Operations\Filament\Resources\StaffMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStaffMembers extends ListRecords
{
    protected static string $resource = StaffMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
