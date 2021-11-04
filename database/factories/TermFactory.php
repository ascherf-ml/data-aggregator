<?php

namespace Database\Factories;

class TermFactory extends CollectionsFactory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return array_merge(
            $this->idsAndTitle(ucfirst($this->faker->word(3, true))),
            [
                'is_category' => false,
                'lake_uid' => 'TM-' . ($this->faker->unique()->randomNumber(6) + 999 * pow(10, 6)),
                'subtype' => $this->faker->randomElement(['TT-1', 'TT-2', 'TT-3', 'TT-4', 'TT-5']),
            ]
        );
    }
}
