<?php

namespace Workbench\App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Minimal user model for the Workbench host application.
 *
 * The operations migrations reference `\App\Models\User` via
 * `foreignIdFor()` (staff_members.user_id), and Filament's Authenticate
 * middleware needs an authenticatable for `actingAs()`. This model serves both
 * roles inside Workbench; the WorkbenchServiceProvider aliases it to the
 * `App\Models\User` class name the migrations expect. Implements FilamentUser
 * so the panel grants access (Filament returns 403 otherwise).
 */
class User extends Authenticatable implements FilamentUser
{
    protected $guarded = [];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
