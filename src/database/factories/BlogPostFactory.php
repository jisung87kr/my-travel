<?php

namespace Database\Factories;

use App\Enums\BlogPostStatus;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogSeries;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence();

        return [
            'user_id' => User::factory(),
            'category_id' => null,
            'series_id' => null,
            'series_order' => null,
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
            'thumbnail_path' => null,
            'status' => BlogPostStatus::DRAFT,
            'published_at' => null,
            'view_count' => 0,
            'like_count' => 0,
            'comment_count' => 0,
            'meta_title' => null,
            'meta_description' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BlogPostStatus::PUBLISHED,
            'published_at' => now(),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BlogPostStatus::DRAFT,
            'published_at' => null,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BlogPostStatus::ARCHIVED,
        ]);
    }

    public function withCategory(?BlogCategory $category = null): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $category?->id ?? BlogCategory::factory(),
        ]);
    }

    public function withSeries(?BlogSeries $series = null, int $order = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'series_id' => $series?->id ?? BlogSeries::factory(),
            'series_order' => $order,
        ]);
    }

    public function withStats(int $views = 100, int $likes = 10, int $comments = 5): static
    {
        return $this->state(fn (array $attributes) => [
            'view_count' => $views,
            'like_count' => $likes,
            'comment_count' => $comments,
        ]);
    }
}
