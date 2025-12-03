<?php

namespace Database\Factories;

use App\Enums\Language;
use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductTranslation>
 */
class ProductTranslationFactory extends Factory
{
    protected $model = ProductTranslation::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'locale' => Language::KO,
            'name' => fake()->sentence(3),
            'description' => fake()->paragraphs(3, true),
            'includes' => fake()->sentences(3, true),
            'excludes' => fake()->sentences(2, true),
            'notes' => fake()->sentence(),
        ];
    }

    public function locale(Language $locale): static
    {
        return $this->state(fn (array $attributes) => [
            'locale' => $locale,
        ]);
    }
}
