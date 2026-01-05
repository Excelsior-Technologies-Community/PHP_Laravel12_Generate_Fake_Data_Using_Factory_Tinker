<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Create 50 posts
        Post::factory()->count(50)->create();

        // Create specific types of posts
        Post::factory()->count(10)->published()->create();
        Post::factory()->count(5)->unpublished()->create();
        Post::factory()->count(7)->popular()->create();

        // Create user with posts
        User::factory()
            ->hasPosts(5)
            ->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com'
            ]);
    }
}