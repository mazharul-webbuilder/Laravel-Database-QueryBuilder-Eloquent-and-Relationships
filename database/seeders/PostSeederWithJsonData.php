<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PostSeederWithJsonData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('database/json/posts.json');

        $posts = collect(json_decode($json));

        $posts->each(function ($post){
            Post::create([
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->excerpt,
                'is_published' => $post->is_published,
                'min_to_read' => $post->min_to_read,
            ]);
        });
    }
}
