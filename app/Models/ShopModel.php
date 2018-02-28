<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\ElasticSearchable;

class ShopModel extends BaseModel
{

    use ElasticSearchable;

    protected static $source = 'Shop';

    protected $primaryKey = 'shop_id';

    protected $dates = ['source_created_at', 'source_modified_at'];

    protected $fakeIdsStartAt = 999000;

    protected function fillIdsFrom($source)
    {

        $this->shop_id = $source->id;

        return $this;

    }

}
