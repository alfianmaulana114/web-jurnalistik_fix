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

        // Get koordinator jurnalistik user
        $koordinator = \App\Models\User::where('role', \App\Models\User::ROLE_KOORDINATOR_JURNALISTIK)->first();
        
        if (!$koordinator) {
            $this->command->warn('Koordinator Jurnalistik tidak ditemukan. Pastikan UserSeeder sudah dijalankan.');
            return;
        }

        foreach ($dummyNews as $news) {
            News::updateOrCreate(
                ['slug' => Str::slug($news['title'])],
                [
                    'user_id' => $koordinator->id,
                    'title' => $news['title'],
                    'content' => $news['content'],
                    'views' => rand(10, 100),
                ]
            );
        }
    }
}