<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'image_path' => 'items/sample.jpg',
            'condition' => $this->faker->numberBetween(1, 4),
            'brand' => $this->faker->optional()->company(),
            'description' => $this->faker->text(255),
            'price' => $this->faker->numberBetween(1,50000),
            'status' => Item::STATUS_ON_SALE,
        ];
    }
}
