<?php

namespace Database\Factories;

use App\Enums\CategoryStatusEnum;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string>
     */
    public function definition(): array
    {
        $category = sprintf("category-%s", fake()->word());
        return [
            'name' => $category,
            'status' => CategoryStatusEnum::ACTIVE->value,
        ];
    }
}
