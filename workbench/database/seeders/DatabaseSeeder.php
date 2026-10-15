<?php

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Saccharine\Operations\Models\CaseFile;
use Saccharine\Operations\Models\Facility;
use Saccharine\Operations\Models\IntakeQueue;
use Saccharine\Operations\Models\StaffMember;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the operational records the Filament resources list: a facility, a
     * staff member, an open case file assigned to that staff member, and a
     * pending public-intake entry.
     */
    public function run(): void
    {
        Facility::firstOrCreate(
            ['name' => 'Workbench Care Centre'],
            [
                'type' => 'care_home',
                'address_city' => 'Toronto',
                'address_country' => 'Canada',
            ],
        );

        $staff = StaffMember::firstOrCreate(
            ['first_name' => 'Avery', 'last_name' => 'Nguyen'],
            [
                'professional_title' => 'Funeral Director',
                'is_active' => true,
            ],
        );

        CaseFile::firstOrCreate(
            ['status' => 'lead'],
            ['assigned_staff_id' => $staff->id],
        );

        IntakeQueue::firstOrCreate(
            ['submitted_name' => 'Jordan Lee'],
            [
                'status' => 'pending',
                'source_channel' => 'Public Website Form',
                'contact_email' => 'jordan@example.test',
                'raw_payload' => ['message' => 'Requesting information about arrangements.'],
            ],
        );
    }
}
