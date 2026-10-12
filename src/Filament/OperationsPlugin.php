<?php

namespace Saccharine\Operations\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Saccharine\Operations\Filament\Resources\CaseFileResource;
use Saccharine\Operations\Filament\Resources\FacilityResource;
use Saccharine\Operations\Filament\Resources\StaffMemberResource;
use Saccharine\Operations\Filament\Resources\IntakeQueueResource;

class OperationsPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'saccharine-operations';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            CaseFileResource::class,
            FacilityResource::class,
            StaffMemberResource::class,
            IntakeQueueResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}