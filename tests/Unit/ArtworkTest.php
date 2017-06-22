<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Collections\Artwork;
use App\Collections\ArtworkDate;
use App\Collections\Image;
use App\Collections\Category;
use App\Collections\Agent;
use App\Collections\AgentType;
use App\Collections\Artist;
use App\Collections\Gallery;

class ArtworkTest extends ApiTestCase
{

    /** @test */
    public function it_fetches_all_artworks()
    {

        $this->it_fetches_all(Artwork::class, 'artworks');
        
    }

    /** @test */
    public function it_fetches_a_single_artwork()
    {

        $this->it_fetches_a_single(Artwork::class, 'artworks');

    }

    /** @test */
    public function it_fetches_multiple_artworks()
    {

        $this->it_fetches_multiple(Artwork::class, 'artworks');

    }


    /** @test */
    public function it_400s_if_nonnumerid_nonuuid_is_passed()
    {

        $this->it_400s(Artwork::class, 'artworks');
        
    }

    /** @test */
    public function it_403s_if_limit_is_too_high()
    {

        $this->it_403s(Artwork::class, 'artworks');

    }

    /** @test */
    public function it_404s_if_not_found()
    {

        $this->it_404s(Artwork::class, 'artworks');

    }

    /** @test */
    public function it_405s_if_a_request_is_posted()
    {

        $this->it_405s(Artwork::class, 'artworks');
        
    }


    /** @test */
    public function it_fetches_images_for_an_artwork()
    {

        $artworkKey = $this->attach(Image::class, 4)->make(Artwork::class);

        $response = $this->getJson('api/v1/artworks/' .$artworkKey .'/images');
        $response->assertSuccessful();

        $images = $response->json()['data'];
        $this->assertCount(4, $images);
        
        foreach ($images as $image)
        {
            $this->assertArrayHasKeys($image, ['id', 'title', 'iiif_url']);
        }
    }
    
    /** @test */
    public function it_fetches_categories_for_an_artwork()
    {

        $artworkKey = $this->attach(Category::class, 4)->make(Artwork::class);

        $response = $this->getJson('api/v1/artworks/' .$artworkKey .'/categories');
        $response->assertSuccessful();
        
        $pubcats = $response->json()['data'];
        $this->assertCount(4, $pubcats);
        
        foreach ($pubcats as $pubcat)
        {
            $this->assertArrayHasKeys($pubcat, ['id', 'title', 'parent_id']);
        }
    }

    public function it_fetches_resources_for_an_artwork()
    {

        $artworkKey = $this->attach([Sound::class, Video::class, Text::class, Link::class], 4)->make(Artwork::class);
        
        $response = $this->getJson('api/v1/artworks/' .$artworkKey .'/resources');
        $response->assertSuccessful();

        $resources = $response->json()['data'];
        $this->assertCount(16, $resources);
        
        foreach ($resources as $resource)
        {
            $this->assertArrayHasKeys($resource, ['id', 'title']);
        }
    }

    /** @test */
    public function it_fetches_the_artists_for_an_artwork()
    {

        $artworkKey = $this->attach(Agent::class, 2, 'artists')->make(Artwork::class);

        $response = $this->getJson('api/v1/artworks/' .$artworkKey .'/artists');
        $response->assertSuccessful();

        $artists = $response->json()['data'];
        $this->assertCount(2, $artists);
        
        foreach ($artists as $artist)
        {
            $this->assertArrayHasKeys($artist, ['id', 'title']);
        }

    }

    /** @test */
    public function it_fetches_the_copyright_representatives_for_an_artwork()
    {

        $copyRepAgentType = $this->make(AgentType::class, ['title' => 'Copyright Representative']);
        $artworkKey = $this->attach(Agent::class, 2, 'copyrightRepresentatives', ['agent_type_citi_id' => $copyRepAgentType])->make(Artwork::class);

        $response = $this->getJson('api/v1/artworks/' .$artworkKey .'/copyrightRepresentatives');
        $response->assertSuccessful();

        $copyrightRepresentatives = $response->json()['data'];
        $this->assertCount(2, $copyrightRepresentatives);
        
        foreach ($copyrightRepresentatives as $copyrightRepresentative)
        {
            $this->assertArrayHasKeys($copyrightRepresentative, ['id', 'title']);
        }

    }


    /** @test */
    public function it_fetches_dates_for_an_artwork()
    {

        $artworkKey = $this->attach(ArtworkDate::class, 4, 'dates')->make(Artwork::class);
        
        $response = $this->getJson('api/v1/artworks/' .$artworkKey);
        $response->assertSuccessful();

        $artwork = $response->json()['data'];

        $this->assertNotEmpty($artwork['dates']);

    }

    /** @test */
    public function it_fetches_the_parts_for_an_artwork()
    {

        $artworkKey = $this->attach(Artwork::class, 2, 'parts')->make(Artwork::class);

        $response = $this->getJson('api/v1/artworks/' .$artworkKey .'/parts');
        $response->assertSuccessful();

        $parts = $response->json()['data'];
        $this->assertCount(2, $parts);
        
        foreach ($parts as $part)
        {
            $this->assertArrayHasKeys($part, ['id', 'title']);
        }

    }

    /** @test */
    public function it_fetches_the_sets_for_an_artwork()
    {

        $artworkKey = $this->attach(Artwork::class, 2, 'sets')->make(Artwork::class);

        $response = $this->getJson('api/v1/artworks/' .$artworkKey .'/sets');
        $response->assertSuccessful();

        $sets = $response->json()['data'];
        $this->assertCount(2, $sets);
        
        foreach ($sets as $set)
        {
            $this->assertArrayHasKeys($set, ['id', 'title']);
        }

    }

}
