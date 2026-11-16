<?php

namespace Database\Seeders;

use App\Enums\TrustLevel;
use App\Models\Errand;
use App\Models\ErrandCategory;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Demo accounts so every teammate can log in as each role right after
 * `php artisan migrate:fresh --seed`. Every password is "password".
 *
 * admin@pasheachi.test             Admin
 * client@pasheachi.test            Client, with two family members and two posted errands
 * runner.dhaka@pasheachi.test      Approved Gold runner in Dhaka
 * runner.sylhet@pasheachi.test     Approved Silver runner in Sylhet
 * runner.chattogram@pasheachi.test Approved Bronze runner in Chattogram
 * runner.pending@pasheachi.test    Runner in Dhaka waiting for admin approval
 *
 * Needs ErrandCategorySeeder to have run first.
 */
class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Demo Admin',
            'email' => 'admin@pasheachi.test',
        ]);

        $client = User::factory()->client()->create([
            'name' => 'Demo Client',
            'email' => 'client@pasheachi.test',
            'country' => 'United Arab Emirates',
            'preferred_currency' => 'AED',
        ]);

        $runners = [
            ['runner.dhaka@pasheachi.test', 'Dhaka', TrustLevel::Gold],
            ['runner.sylhet@pasheachi.test', 'Sylhet', TrustLevel::Silver],
            ['runner.chattogram@pasheachi.test', 'Chattogram', TrustLevel::Bronze],
        ];

        foreach ($runners as [$email, $district, $level]) {
            User::factory()->runner($district)->trustLevel($level)->create([
                'name' => "Demo Runner ({$district})",
                'email' => $email,
                'verified_by' => $admin->id,
            ]);
        }

        User::factory()->runner('Dhaka')->pendingVerification()->create([
            'name' => 'Pending Runner (Dhaka)',
            'email' => 'runner.pending@pasheachi.test',
        ]);

        $mother = FamilyMember::factory()->for($client, 'client')->create([
            'name' => 'Ayesha Begum',
            'relation' => 'Mother',
            'district' => 'Dhaka',
            'care_notes' => 'Diabetic, needs insulin from the pharmacy every month.',
        ]);

        $father = FamilyMember::factory()->for($client, 'client')->create([
            'name' => 'Abdul Karim',
            'relation' => 'Father',
            'district' => 'Sylhet',
            'care_notes' => 'Uses a wheelchair.',
        ]);

        Errand::factory()->for($mother)->for(ErrandCategory::where('name', 'Medicine delivery')->first(), 'category')->create([
            'description' => 'Pick up the monthly insulin from Lazz Pharma and deliver it home.',
        ]);

        Errand::factory()->for($father)->for(ErrandCategory::where('name', 'Doctor visit escort')->first(), 'category')->create([
            'description' => 'Take him to his check-up at the district hospital and bring him back.',
        ]);
    }
}
