<?php

use Saccharine\Operations\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Bind the Workbench-aware Testbench TestCase to the package's test suites.
| Providers, migrations, and seeders are declared in `testbench.yaml` (the
| OperationsServiceProvider plus a minimal Filament panel wiring the
| OperationsPlugin, mirroring the host app configuration).
|
*/

pest()->extend(TestCase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});
