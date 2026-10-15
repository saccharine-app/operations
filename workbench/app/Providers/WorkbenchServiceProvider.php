<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Workbench host application service provider for the Operations package.
 *
 * The package migrations reference `\App\Models\User` (staff_members.user_id
 * is built with `foreignIdFor(\App\Models\User::class)`), which only exists in
 * a real host application. Here we alias the Workbench user model to that class
 * name so migrations resolve without a host app. The alias is registered in
 * `register()` — before any migration runs.
 */
class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (! class_exists('App\Models\User', false)) {
            class_alias(\Workbench\App\Models\User::class, 'App\Models\User');
        }

        // Filament Shield-style abilities (e.g. "ViewAny:CaseFile") are used by
        // the package's policies but are not registered in a plain host app.
        // Grant them here so panel resources are reachable in tests without
        // requiring the Shield package to be installed locally.
        Gate::before(function ($user, string $ability) {
            // Shield abilities look like "Verb:Model" (e.g. "ViewAny:CaseFile").
            if (str_contains($ability, ':')) {
                return true;
            }

            return null; // defer to other gates/policies
        });
    }
}
