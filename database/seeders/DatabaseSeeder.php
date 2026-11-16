<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Run with `php artisan migrate:fresh --seed` to rebuild every table and
     * load the same test data on every teammate's machine.
     */
    public function run(): void
    {
        $this->call([
            CurrencyRateSeeder::class,
            ErrandCategorySeeder::class,
            DemoUserSeeder::class,
        ]);
    }
}
