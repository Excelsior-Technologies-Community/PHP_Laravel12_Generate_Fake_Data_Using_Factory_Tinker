<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $categories = ['Technology', 'Science', 'Design', 'Business', 'Lifestyle', 'Education'];

        return [
            'title' => $this->faker->sentence(rand(4, 8)),
            'category' => $this->faker->randomElement($categories),
            'content' => $this->faker->paragraphs(rand(2, 5), true),
            'author_id' => User::factory(),
            'is_published' => $this->faker->boolean(80),
            'views' => $this->faker->numberBetween(50, 12000),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => $attributes['published_at'] ?? now(),
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function popular(int $minViews = 15000, int $maxViews = 95000): static
    {
        return $this->state(fn (array $attributes) => [
            'views' => $this->faker->numberBetween($minViews, $maxViews),
            'is_published' => true,
        ]);
    }

    public function category(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => $category,
        ]);
    }

    public function author(User|int $user): static
    {
        return $this->state(fn (array $attributes) => [
            'author_id' => $user instanceof User ? $user->id : $user,
        ]);
    }
}