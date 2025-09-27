<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $news = News::all();

        foreach ($news as $newsItem) {
            $commentCount = rand(2, 5);
            
            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'news_id' => $newsItem->id,
                    'name' => 'Pengunjung ' . ($i + 1),
                    'email' => 'pengunjung' . ($i + 1) . '@example.com',
                    'content' => 'Ini adalah komentar untuk berita "' . $newsItem->title . '". Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                ]);
            }
        }
    }
}