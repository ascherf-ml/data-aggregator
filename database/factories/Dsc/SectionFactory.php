<?php

namespace Database\Factories\Dsc;

use App\Models\Dsc\Section;
use App\Models\Dsc\Publication;
use App\Models\Collections\Artwork;

class SectionFactory extends DscFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string|null
     */
    protected $model = Section::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return array_merge(
            $this->dscIdsAndTitle(),
            [

                'web_url' => $this->faker->url,
                'accession' => $this->faker->accession,
                'revision' => rand(1230768000, 1483228800), // timestamp b/w 2009 and 2017
                'source_id' => $this->faker->randomNumber(5),
                'weight' => $this->faker->randomNumber(2),
                'parent_id' => !rand(0, 3) ? null : $this->faker->randomElement(Section::query()->pluck('dsc_id')->all()),
                'publication_dsc_id' => $this->faker->randomElement(Publication::query()->pluck('dsc_id')->all()),
                'artwork_citi_id' => $this->faker->randomElement(Artwork::query()->pluck('citi_id')->all()),
                'content' => $this->faker->paragraphs(10, true),

            ]
        );
    }
}
