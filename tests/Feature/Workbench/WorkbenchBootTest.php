<?php

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Saccharine\Operations\Filament\Resources\CaseFileResource;
use Saccharine\Operations\Filament\Resources\FacilityResource;
use Saccharine\Operations\Filament\Resources\IntakeQueueResource;
use Saccharine\Operations\Filament\Resources\StaffMemberResource;
use Saccharine\Operations\Models\CaseFile;
use Saccharine\Operations\Models\Facility;
use Saccharine\Operations\Models\IntakeQueue;
use Saccharine\Operations\Models\StaffMember;
use Saccharine\Operations\OperationsServiceProvider;
use Workbench\Database\Seeders\DatabaseSeeder;

/*
|--------------------------------------------------------------------------
| Workbench Integration Test
|--------------------------------------------------------------------------
|
| Boots the Operations package inside Orchestra Workbench with no host app:
| providers, migrations, the workbench seeder (which exercises the
| `\App\Models\User` alias required by the staff_members FK), the public
| intake routes, and the Filament panel wired through the OperationsPlugin.
|
*/

it('boots the package provider via testbench.yaml', function () {
    expect($this->app->getProviders(OperationsServiceProvider::class))->not->toBeEmpty();
});

it('registers the case_file morph alias', function () {
    expect(Relation::getMorphedModel('case_file'))->toBe(CaseFile::class);
});

it('aliases the workbench user model to App\\Models\\User for the migrations', function () {
    expect(class_exists('App\\Models\\User'))->toBeTrue()
        ->and(is_a('App\\Models\\User', \Workbench\App\Models\User::class, true))->toBeTrue();
});

it('runs the package migrations and workbench seeder', function () {
    $this->seed(DatabaseSeeder::class);

    expect(StaffMember::where('first_name', 'Avery')->exists())->toBeTrue()
        ->and(Facility::where('name', 'Workbench Care Centre')->exists())->toBeTrue()
        ->and(CaseFile::where('status', 'lead')->exists())->toBeTrue()
        ->and(IntakeQueue::where('submitted_name', 'Jordan Lee')->exists())->toBeTrue();
});

it('assigns seeded case files to seeded staff through the users FK', function () {
    $this->seed(DatabaseSeeder::class);

    $case = CaseFile::firstOrFail();

    expect($case->assignedStaff)->not->toBeNull()
        ->and($case->assignedStaff->first_name)->toBe('Avery');
});

it('renders the public intake form route', function () {
    expect(Route::getRoutes()->getRoutesByName())->toHaveKeys(['intake.create', 'intake.store']);

    $this->get(route('intake.create'))
        ->assertOk()
        ->assertSee('submitted_name');
});

it('stores a public intake submission', function () {
    $response = $this->post(route('intake.store'), [
        'submitted_name' => 'Casey Morgan',
        'contact_email' => 'casey@example.test',
        'message' => 'Please contact me about arrangements.',
    ]);

    $response->assertRedirect();

    $intake = IntakeQueue::where('submitted_name', 'Casey Morgan')->first();
    expect($intake)->not->toBeNull()
        ->and($intake->status)->toBe('pending')
        ->and($intake->source_channel)->toBe('Public Website Form')
        ->and($intake->raw_payload)->toBe(['message' => 'Please contact me about arrangements.']);
});

it('validates the public intake submission', function () {
    $this->post(route('intake.store'), [])
        ->assertSessionHasErrors(['submitted_name']);

    expect(IntakeQueue::count())->toBe(0);
});

it('registers the operations resources on the workbench Filament panel', function () {
    // The test boots the panel via FilamentPanelProvider, but no HTTP request
    // has run yet, so Filament::getCurrentPanel() is null. Pull the registered
    // panel by id instead, which is the same panel the OperationsPlugin
    // attaches its resources to.
    $panel = Filament::getPanel('workbench');

    expect($panel)->not->toBeNull()
        ->and($panel->getResources())->toContain(
            CaseFileResource::class,
            FacilityResource::class,
            StaffMemberResource::class,
            IntakeQueueResource::class,
        );
});

it('renders the case files list page in the Filament panel', function () {
    $this->seed(DatabaseSeeder::class);
    $this->authenticateWorkbenchUser();

    $response = $this->get('/workbench-admin/case-files');

    $response->assertOk();
    expect($response->getContent())->toContain('Case File');
});
