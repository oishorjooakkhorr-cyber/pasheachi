<?php

namespace Database\Seeders;

use App\Models\ErrandCategory;
use Illuminate\Database\Seeder;

/**
 * The errand types listed in the SRS scope (section 1.2), with sample base
 * fees in BDT. The admin can change them in the app (F19).
 */
class ErrandCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Doctor visit escort' => 600,
            'Medicine delivery' => 300,
            'Grocery delivery' => 300,
            'Bill payment' => 200,
            'Eid gift shopping' => 500,
            'Property check' => 400,
            'Document collection' => 400,
        ];

        foreach ($categories as $name => $fee) {
            ErrandCategory::updateOrCreate(['name' => $name], ['base_service_fee' => $fee, 'is_active' => true]);
        }
    }
}
