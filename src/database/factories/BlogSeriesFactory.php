<?php

namespace Database\Factories;

use App\Models\BlogSeries;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogSeries>
 */
class BlogSeriesFactory extends Factory
{
    protected $model = BlogSeries::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'thumbnail_path' => null,
            'post_count' => 0,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withPostCount(int $count): static
    {
        return $this->state(fn (array $attributes) => [
            'post_count' => $count,
        ]);
    }
}
