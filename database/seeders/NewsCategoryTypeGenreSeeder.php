<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsCategory;
use App\Models\NewsType;
use App\Models\NewsGenre;

class NewsCategoryTypeGenreSeeder extends Seeder
{
    public function run(): void
    {
        // Seed News Categories
        $categories = ['Berita Nasional', 'Berita Internasional', 'Berita Internal'];
        foreach ($categories as $category) {
            NewsCategory::updateOrCreate(['name' => $category]);
        }

        // Seed News Types
        $types = ['Berita Harian', 'Berita Terkini', 'Press Release', 'Media Partner'];
        foreach ($types as $type) {
            NewsType::updateOrCreate(['name' => $type]);
        }

        // Seed News Genres
        $genres = ['Hiburan', 'Inspirasi', 'Nasional', 'Olahraga', 'Peristiwa', 'Politik', 'Teknologi', 'UBSI'];
        foreach ($genres as $genre) {
            NewsGenre::updateOrCreate(['name' => $genre]);
        }
    }
}