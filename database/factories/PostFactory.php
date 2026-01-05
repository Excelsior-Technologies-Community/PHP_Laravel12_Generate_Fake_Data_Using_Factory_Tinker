<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'content' => $this->faker->paragraphs(3, true),
            'author' => $this->faker->name(),
            'is_published' => $this->faker->boolean(80), // 80% chance of true
            'views' => $this->faker->numberBetween(0, 10000),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    // State method for published posts
    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => true,
                'published_at' => now(),
            ];
        });
    }

    // State method for unpublished posts
    public function unpublished()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => false,
                'published_at' => null,
            ];
        });
    }

    // State method for popular posts
    public function popular()
    {
        return $this->state(function (array $attributes) {
            return [
                'views' => $this->faker->numberBetween(5000, 100000),
            ];
        });
    }
}