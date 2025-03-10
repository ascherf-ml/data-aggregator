<?php

namespace Database\Factories\Collections;

use App\Models\Collections\Artwork;
use App\Models\Collections\Agent;
use App\Models\Collections\AgentType;
use App\Models\Collections\ArtworkType;
use App\Models\Collections\Place;

class ArtworkFactory extends CollectionsFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string|null
     */
    protected $model = Artwork::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date_end = $this->faker->year;
        $artist = Agent::where('agent_type_citi_id', AgentType::where('title', 'Individual')->first()->citi_id)->get()->random();
        return array_merge(
            $this->idsAndTitle(ucwords($this->faker->words(4, true)), true, 6),
            [
                'main_id' => $this->faker->accession,
                'date_display' => '' . $date_end,
                'date_start' => $this->faker->year,
                'date_end' => $date_end,
                'description' => $this->faker->paragraph(5),
                'artist_display' => $artist->title_raw . "\n" . $this->faker->country . ', ' . $this->faker->year . '–' . $this->faker->year,
                'dimensions' => $this->faker->randomFloat(1, 0, 200) . ' x ' . $this->faker->randomFloat(1, 0, 200) . ' (' . $this->faker->randomNumber(2) . $this->faker->randomElement(['', ' 1/8', ' 1/4', ' 3/8', ' 1/2', ' 5/8', ' 3/4', ' 7/8']) . ' x ' . $this->faker->randomNumber(2) . $this->faker->randomElement(['', ' 1/8', ' 1/4', ' 3/8', ' 1/2', ' 5/8', ' 3/4', ' 7/8']) . ' in.)',
                'medium_display' => ucfirst($this->faker->word) . ' on ' . $this->faker->word,
                'credit_line' => $this->faker->randomElement(['', 'Friends of ', 'Gift of ', 'Bequest of ']) . $this->faker->words(3, true),
                'inscriptions' => $this->faker->words(4, true),
                'publication_history' => $this->faker->paragraph(5),
                'exhibition_history' => $this->faker->paragraph(5),
                'provenance' => $this->faker->paragraph(5),
                'publishing_verification_level' => $this->faker->randomElement(['Web Basic', 'Web Cataloged', 'Web Everything']),
                'is_public_domain' => $this->faker->boolean,
                'copyright_notice' => '© ' . $this->faker->year . ' ' . ucfirst($this->faker->words(3, true)),
                'place_of_origin' => $this->faker->country,
                'collection_status' => $this->faker->randomElement(['Permanent Collection', 'Long-term Loan']),
                'artwork_type_citi_id' => $this->faker->randomElement(ArtworkType::query()->pluck('citi_id')->all()),
                'gallery_citi_id' => $this->faker->randomElement(Place::query()->pluck('citi_id')->all()),
            ],
            $this->dates(true)
        );
    }
}
