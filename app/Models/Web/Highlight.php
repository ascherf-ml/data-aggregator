<?php

namespace App\Models\Web;

use App\Models\WebModel;

/**
 * Highlights are a grouping of artworks on the website
 */
class Highlight extends WebModel
{

    protected $casts = [
        'published' => 'boolean',
        'publish_start_date' => 'datetime',
        'publish_end_date' => 'datetime',
    ];
}
