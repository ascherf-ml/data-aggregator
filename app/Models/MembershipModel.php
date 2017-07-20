<?php

namespace App\Models;

use App\Models\BaseModel;

use App\Scopes\SortByLastUpdatedScope;

class MembershipModel extends BaseModel
{

    public $incrementing = false;
    protected $primaryKey = 'membership_id';
    protected $dates = ['source_created_at', 'source_modified_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected static function boot()
    {

        parent::boot();
        static::addGlobalScope(new SortByLastUpdatedScope());

    }

}
