<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ResourceServiceProvider extends ServiceProvider
{


    public function boot()
    {
        //
    }

    public function register()
    {

        $this->app->singleton('Resources', function($app) {

            return new class {

                /**
                 * Array of resources (endpoints), mapped to their models.
                 * Currently, only top-level endpoints are tracked.
                 *
                 * @TODO Should this live in a config file?
                 * @TODO Use this space to tag things as searchable?
                 *
                 * @var array
                 */
                private $resources = [
                    [
                        'endpoint' => 'artworks',
                        'model' => \App\Models\Collections\Artwork::class,
                    ],
                    [
                        'endpoint' => 'agents',
                        'model' => \App\Models\Collections\Agent::class,
                    ],
                    [
                        'endpoint' => 'artists',
                        'model' => \App\Models\Collections\Agent::class,
                        'scope_of' => 'agents',
                    ],
                    [
                        'endpoint' => 'venues',
                        'model' => \App\Models\Collections\Agent::class,
                        'scope_of' => 'agents',
                    ],
                    [
                        'endpoint' => 'agent-places',
                        'model' => \App\Models\Collections\AgentPlace::class,
                    ],
                    [
                        'endpoint' => 'agent-types',
                        'model' => \App\Models\Collections\AgentType::class,
                    ],
                    [
                        'endpoint' => 'agent-roles',
                        'model' => \App\Models\Collections\AgentRole::class,
                    ],
                    [
                        'endpoint' => 'artwork-types',
                        'model' => \App\Models\Collections\ArtworkType::class,
                    ],
                    [
                        'endpoint' => 'artwork-place-qualifiers',
                        'model' => \App\Models\Collections\ArtworkPlaceQualifier::class,
                    ],
                    [
                        'endpoint' => 'artwork-date-qualifiers',
                        'model' => \App\Models\Collections\ArtworkDateQualifier::class,
                    ],
                    [
                        'endpoint' => 'artwork-dates',
                        'model' => \App\Models\Collections\ArtworkDate::class,
                    ],
                    [
                        'endpoint' => 'categories',
                        'model' => \App\Models\Collections\Category::class,
                    ],
                    [
                        'endpoint' => 'departments',
                        'model' => \App\Models\Collections\Category::class,
                        'scope_of' => 'categories',
                    ],
                    [
                        'endpoint' => 'places',
                        'model' => \App\Models\Collections\Place::class,
                    ],
                    [
                        'endpoint' => 'catalogues',
                        'model' => \App\Models\Collections\Catalogue::class,
                    ],
                    [
                        'endpoint' => 'artwork-catalogues',
                        'model' => \App\Models\Collections\ArtworkCatalogue::class,
                    ],
                    [
                        'endpoint' => 'galleries',
                        'model' => \App\Models\Collections\Gallery::class,
                    ],
                    [
                        'endpoint' => 'exhibitions',
                        'model' => \App\Models\Collections\Exhibition::class,
                    ],
                    [
                        'endpoint' => 'exhibition-agents',
                        'model' => \App\Models\Collections\AgentExhibition::class,
                    ],
                    [
                        'endpoint' => 'terms',
                        'model' => \App\Models\Collections\Term::class,
                    ],
                    [
                        'endpoint' => 'term-types',
                        'model' => \App\Models\Collections\TermType::class,
                    ],
                    [
                        'endpoint' => 'category-terms',
                        'model' => \App\Models\Collections\CategoryTerm::class,
                    ],
                    [
                        'endpoint' => 'assets',
                        'model' => \App\Models\Collections\Asset::class,
                    ],
                    [
                        'endpoint' => 'images',
                        'model' => \App\Models\Collections\Image::class,
                    ],
                    [
                        'endpoint' => 'videos',
                        'model' => \App\Models\Collections\Video::class,
                    ],
                    [
                        'endpoint' => 'links',
                        'model' => \App\Models\Collections\Link::class,
                    ],
                    [
                        'endpoint' => 'sounds',
                        'model' => \App\Models\Collections\Sound::class,
                    ],
                    [
                        'endpoint' => 'texts',
                        'model' => \App\Models\Collections\Text::class,
                    ],
                    [
                        'endpoint' => 'shop-categories',
                        'model' => \App\Models\Shop\Category::class,
                    ],
                    [
                        'endpoint' => 'products',
                        'model' => \App\Models\Shop\Product::class,
                    ],
                    [
                        'endpoint' => 'legacy-events',
                        'model' => \App\Models\Membership\LegacyEvent::class,
                    ],
                    [
                        'endpoint' => 'ticketed-events',
                        'model' => \App\Models\Membership\TicketedEvent::class,
                    ],
                    [
                        'endpoint' => 'tours',
                        'model' => \App\Models\Mobile\Tour::class,
                    ],
                    [
                        'endpoint' => 'tour-stops',
                        'model' => \App\Models\Mobile\TourStop::class,
                    ],
                    [
                        'endpoint' => 'mobile-sounds',
                        'model' => \App\Models\Mobile\Sound::class,
                    ],
                    [
                        'endpoint' => 'publications',
                        'model' => \App\Models\Dsc\Publication::class,
                    ],
                    [
                        'endpoint' => 'sections',
                        'model' => \App\Models\Dsc\Section::class,
                    ],
                    [
                        'endpoint' => 'sites',
                        'model' => \App\Models\StaticArchive\Site::class,
                    ],
                    [
                        'endpoint' => 'library-materials',
                        'model' => \App\Models\Library\Material::class,
                    ],
                    [
                        'endpoint' => 'library-terms',
                        'model' => \App\Models\Library\Term::class,
                    ],
                    [
                        'endpoint' => 'archive-images',
                        'model' => \App\Models\Archive\ArchiveImage::class,
                    ],
                    [
                        'endpoint' => 'tags',
                        'model' => \App\Models\Web\Tag::class,
                    ],
                    [
                        'endpoint' => 'locations',
                        'model' => \App\Models\Web\Location::class,
                    ],
                    [
                        'endpoint' => 'hours',
                        'model' => \App\Models\Web\Hour::class,
                    ],
                    [
                        'endpoint' => 'closures',
                        'model' => \App\Models\Web\Closure::class,
                    ],
                    [
                        'endpoint' => 'web-exhibitions',
                        'model' => \App\Models\Web\Exhibition::class,
                    ],
                    [
                        'endpoint' => 'events',
                        'model' => \App\Models\Web\Event::class,
                    ],
                    [
                        'endpoint' => 'articles',
                        'model' => \App\Models\Web\Article::class,
                    ],
                    [
                        'endpoint' => 'selections',
                        'model' => \App\Models\Web\Selection::class,
                    ],
                    [
                        'endpoint' => 'web-artists',
                        'model' => \App\Models\Web\Artist::class,
                    ],
                    [
                        'endpoint' => 'pages',
                        'model' => \App\Models\Web\Page::class,
                    ],
                ];

                /**
                 * Init this class. Transforms `$resources` into an Eloquent collection.
                 */
                public function __construct()
                {
                    $this->resources = collect( $this->resources );
                }

                public function getModelForEndpoint( $endpoint )
                {
                    $resource = $this->resources->firstWhere('endpoint', $endpoint);

                    $model = $resource['model'] ?? null;

                    if( !$model )
                    {
                        throw new \Exception('You must define a model for outbound endpoint `' . $endpoint . '` in ResourceServiceProvider.');
                    }

                    return $model;
                }

                public function getEndpointForModel( $model )
                {

                    // Remove \ from start of $model if present
                    $model = ltrim( $model, '\\' );

                    $resource = $this->resources->firstWhere('model', $model);

                    $endpoint = $resource['endpoint'] ?? null;

                    if( !$endpoint )
                    {
                        throw new \Exception('You must define an outbound endpoint for model `' . $model . '` in ResourceServiceProvider.');
                    }

                    return $endpoint;
                }

                public function getParent( $endpoint )
                {

                    $resource = $this->resources->firstWhere('endpoint', $endpoint);

                    return $resource['scope_of'] ?? null;
                }

            };

        });

    }
}
