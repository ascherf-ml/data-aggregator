<?php

namespace Tests\Unit;

use App\Models\Collections\Link;

class LinkTest extends ApiTestCase
{

    protected $model = Link::class;

    protected $route = 'links';

    /** @test */
    public function it_404s_if_not_found()
    {

        $this->it_404s(Link::class, 'links', true);

    }

}
