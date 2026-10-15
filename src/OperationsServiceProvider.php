<?php

namespace Saccharine\Operations;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class OperationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/operations.php', 'saccharine');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'saccharine');

        // Register the package's Blade views (e.g. the public intake form)
        // under the `saccharine::` namespace.
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'saccharine');

        // Register the public-facing intake routes (/intake, /submit-intake).
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        Relation::morphMap([
            'case_file' => \Saccharine\Operations\Models\CaseFile::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/operations.php' => config_path('operations.php'),
            ], 'saccharine-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'saccharine-migrations');

            $this->publishes([
                __DIR__ . '/../resources/lang' => $this->app->langPath('vendor/saccharine'),
            ], 'saccharine-translations');
        }
    }
}