<?php

namespace Saccharine\Operations\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Workbench\App\Models\User;

class TestCase extends Orchestra
{
    use WithWorkbench;
    use RefreshDatabase;

    /**
     * Enable package auto-discovery so Filament and Livewire boot exactly as
     * they would in a real host application.
     */
    protected $enablesPackageDiscoveries = true;

    protected function setUp(): void
    {
        parent::setUp();

        // Loads the testbench skeleton's core migrations. The staff_members
        // migration has a foreign key to `users`, so this must run before the
        // package's own migrations (RefreshDatabase handles ordering).
        $this->loadLaravelMigrations();
    }

    /**
     * Define environment setup.
     *
     * Workbench reads package providers from `testbench.yaml`; this hook only
     * tunes runtime config for the test environment.
     */
    protected function getEnvironmentSetUp($app)
    {
        // Provide a standard 32-character base64 string for Laravel's encrypter
        $app['config']->set('app.key', 'base64:Hupx3yAySikrM2/edkZQNQHslgDWYvnkFmTNFjq+95U=');

        // Force all database connections to use in-memory sqlite
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Filament needs a session-backed guard for its page middleware stack.
        $app['config']->set('session.driver', 'array');
    }

    /**
     * Create (and authenticate) a Workbench user for Filament-protected pages.
     */
    protected function authenticateWorkbenchUser(): User
    {
        $user = new User([
            'name' => 'Workbench User',
            'email' => 'workbench@example.test',
        ]);
        $user->id = 1;

        $this->actingAs($user);

        return $user;
    }
}
