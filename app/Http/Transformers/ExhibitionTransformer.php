<?php

namespace App\Http\Transformers;

use App\Models\Collections\Exhibition;

class ExhibitionTransformer extends CollectionsTransformer
{

    public $citiObject = true;

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = ['artworks', 'venues'];


    /**
     * Include artworks.
     *
     * @param  \App\Models\Collections\Exhibition  $exhibition
     * @return League\Fractal\ItemResource
     */
    public function includeArtworks(Exhibition $exhibition)
    {
        return $this->collection($exhibition->artworks()->getResults(), new ArtworkTransformer, false);
    }

    
    /**
     * Include venues.
     *
     * @param  \App\Models\Collections\Exhibition  $exhibition
     * @return League\Fractal\ItemResource
     */
    public function includeVenues(Exhibition $exhibition)
    {
        return $this->collection($exhibition->venues()->getResults(), new AgentTransformer, false);
    }

}