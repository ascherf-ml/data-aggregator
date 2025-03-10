<?php

namespace Database\Factories\Collections;

use App\Models\Collections\ArtworkDate;

class ArtworkDateFactory extends CollectionsFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string|null
     */
    protected $model = ArtworkDate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return array_merge(
            $this->idsAndTitle($this->faker->word . ' date', true),
            [
                'artwork_citi_id' => $this->faker->randomElement(App\Models\Collections\Artwork::query()->pluck('citi_id')->all()),
                'date_earliest' => $this->faker->dateTimeAd,
                'date_latest' => $this->faker->dateTimeAd,
                'artwork_date_qualifier_citi_id' => $this->faker->randomElement(App\Models\Collections\ArtworkDateQualifier::query()->pluck('citi_id')->all()),
                'preferred' => $this->faker->boolean,
            ]
        );
    }
}
