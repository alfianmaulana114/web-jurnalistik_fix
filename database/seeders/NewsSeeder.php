<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyNews = [
            [
                'title' => 'Kegiatan UKM Jurnalistik Tahun 2024',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies aliquam, nunc nisl aliquet nunc, quis aliquam nisl nunc quis nisl. Nullam auctor, nisl eget ultricies aliquam, nunc nisl aliquet nunc, quis aliquam nisl nunc quis nisl.',
            ],
            [
                'title' => 'Workshop Penulisan Artikel Ilmiah',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies aliquam, nunc nisl aliquet nunc, quis aliquam nisl nunc quis nisl. Nullam auctor, nisl eget ultricies aliquam, nunc nisl aliquet nunc, quis aliquam nisl nunc quis nisl.',
            ],
            [
                'title' => 'Peliputan Acara Kampus',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies aliquam, nunc nisl aliquet nunc, quis aliquam nisl nunc quis nisl. Nullam auctor, nisl eget ultricies aliquam, nunc nisl aliquet nunc, quis aliquam nisl nunc quis nisl.',
            ],
        ];

        foreach ($dummyNews as $news) {
            News::create([
                'user_id' => 1, // Admin ID
                'title' => $news['title'],
                'slug' => Str::slug($news['title']),
                'content' => $news['content'],
                'views' => rand(10, 100),
            ]);
        }
    }
}