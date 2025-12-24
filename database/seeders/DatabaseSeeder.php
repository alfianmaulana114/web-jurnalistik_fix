<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            NewsCategoryTypeGenreSeeder::class,
            UserSeeder::class,
            NewsSeeder::class,
            ProkerSeeder::class, // Moved up
            DesignSeeder::class, 
            BriefSeeder::class,
            ContentSeeder::class,
        ]);
    }
}
