<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::create([
            'title' => 'Learn Laravel Easily',
            'body' => 'Laravel is a great PHP framework for beginners and professionals.',
        ]);

        Post::create([
            'title' => 'Laravel Site Search Example',
            'body' => 'This tutorial shows how to add site search to your Laravel app.',
        ]);

        Post::create([
            'title' => 'Advanced PHP Techniques',
            'body' => 'Learn advanced PHP topics and improve your coding skills.',
        ]);
    }
}