<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'name' => $faker->word,
            'description' => $faker->paragraph,
            'price' => $faker->numberBetween(100,1000),
            'stock'=> $faker->randomDigit,
            'old_price' => $faker->numberBetween(100,1000),
            'discount' => $faker->numberBetween(2,30),
            'code_bare' => $faker->numberBetween(100,1000),
        ];
    }
}
