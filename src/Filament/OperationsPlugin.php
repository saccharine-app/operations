<?php

namespace Saccharine\Operations\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Saccharine\Operations\Filament\Resources\CaseFileResource;
use Saccharine\Operations\Filament\Resources\FacilityResource;
use Saccharine\Operations\Filament\Resources\IntakeQueueResource;
use Saccharine\Operations\Filament\Resources\StaffMemberResource;

class OperationsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'saccharine-operations';
    }

    public function getPluginName(): string
    {
        return 'Operations';
    }

    public function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                CaseFileResource::class,
                FacilityResource::class,
                IntakeQueueResource::class,
                StaffMemberResource::class,
            ])
            ->pages([])
            ->middleware([]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
