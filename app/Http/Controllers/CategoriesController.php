<?php

namespace App\Http\Controllers;

use App\Models\Collections\Category;

use Illuminate\Http\Request;

use App\Http\Controllers\AbstractController as BaseController;

class CategoriesController extends BaseController
{

    protected $model = \App\Models\Collections\Category::class;

    protected $transformer = \App\Http\Transformers\CollectionsTransformer::class;

}
