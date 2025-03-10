<?php

namespace Database\Factories\Shop;

use Illuminate\Database\Eloquent\Factories\Factory;

abstract class ShopFactory extends Factory
{
    public function shopIdsAndTitle($title = '')
    {
        return [
            'shop_id' => $this->faker->unique()->randomNumber(3) + 999 * pow(10, 3),
            'title' => $title ? $title : ucfirst($this->faker->words(5, true)),
        ];
    }

    public function shopDates()
    {
        return [
            'source_modified_at' => $this->faker->dateTimeThisYear,
        ];
    }
}
