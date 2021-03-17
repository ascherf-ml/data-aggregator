<?php

namespace App\Models\Shop;

use App\Models\ShopModel;

/**
 * An item available for purchase in the museum shop.
 */
class Product extends ShopModel
{
    protected $casts = [
        'source_created_at' => 'datetime',
    ];

    public function artists()
    {
        return $this->belongsToMany('App\Models\Collections\Agent', 'artist_product');
    }
}
