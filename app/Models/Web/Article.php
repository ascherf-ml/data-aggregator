<?php

namespace App\Models\Web;

use App\Models\WebModel;

/**
 * Article on the website
 */
class Article extends WebModel
{

    protected $casts = [
        'published' => 'boolean',
        'date' => 'date',
    ];

}
