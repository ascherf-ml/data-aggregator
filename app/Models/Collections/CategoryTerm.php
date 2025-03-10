<?php

namespace App\Models\Collections;

use App\Models\CollectionsModel;
use App\Models\ElasticSearchable;

/**
 * Tag-like classifications of artworks and other resources.
 */
class CategoryTerm extends CollectionsModel
{

    use ElasticSearchable;

    public const CLASSIFICATION = 'TT-1';
    public const MATERIAL = 'TT-2';
    public const TECHNIQUE = 'TT-3';
    public const STYLE = 'TT-4';
    public const SUBJECT = 'TT-5';

    public const DEPARTMENT = 'CT-1';
    public const THEME = 'CT-3';

    protected static $isCategory = null;

    protected $primaryKey = 'lake_uid';
    protected $keyType = 'string';

    // This propogates to Category and Term
    protected $table = 'category_terms';

    protected $touches = [
        'artworks',
    ];

    /**
     * Create a new instance of the given model. For Assets, we use this to set a default `type`.
     */
    public function __construct()
    {
        parent::__construct(...func_get_args());

        $this->is_category = static::$isCategory ?? null;
    }

    // This also propogates to Category and Term
    // Affects `searchableIndex` and `api_model`
    public function searchableModel()
    {
        return 'category-terms';
    }

    /**
     * The artworks that belong to the category or term.
     */
    public function artworks()
    {
        $table = $this->is_category ? 'artwork_category' : 'artwork_term';
        $column = $this->is_category ? 'category_lake_uid' : 'term_lake_uid';
        return $this->belongsToMany('App\Models\Collections\Artwork', $table, $column)->artworks();
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Collections\CategoryTerm', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Collections\CategoryTerm', 'parent_id');
    }

    /**
     * Scope a query to only include index terms.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTerms($query)
    {
        return $query->where('is_category', false);
    }

    public static function searchScopeTerms()
    {
        return [
            'prefix' => [
                'id' => 'TM-',
            ],
        ];
    }

    /**
     * Scope a query to only include categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategories($query)
    {
        return $query->where('is_category', true);
    }

    public static function searchScopeCategories()
    {
        return [
            'prefix' => [
                'id' => 'PC-',
            ],
        ];
    }

    /**
     * Scope a query to only include categories that represent departments
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDepartments($query)
    {
        return $query->categories()->where('subtype', self::DEPARTMENT)->where('parent_id', null);
    }

    /**
     * Scope a search to only include top-level departmental categories.
     *
     * @return array
     */
    public static function searchScopeDepartments()
    {
        return [
            'bool' => [
                'must' => [
                    'term' => [
                        'subtype' => self::DEPARTMENT,
                    ],
                ],
                'must_not' => [
                    'exists' => [
                        'field' => 'parent_id',
                    ],
                ],
            ],
        ];
    }

    /**
     * Scope a query to only include categories that represent themes
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThemes($query)
    {
        return $query->categories()->where('subtype', self::THEME);
    }

    /**
     * Scope a query to only include terms that are of type 'style'.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStyle($query)
    {
        return $query->terms()->where('subtype', self::STYLE);
    }

    /**
     * Scope a query to only include terms that are of type 'classification'.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClassification($query)
    {
        return $query->terms()->where('subtype', self::CLASSIFICATION);
    }

    /**
     * Scope a query to only include terms that are of type 'subject'.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubject($query)
    {
        return $query->terms()->where('subtype', self::SUBJECT);
    }

    /**
     * Scope a query to only include terms that are of type 'material'.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMaterial($query)
    {
        return $query->terms()->where('subtype', self::MATERIAL);
    }

    /**
     * Scope a query to only include terms that are of type 'theme'.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTechnique($query)
    {
        return $query->terms()->where('subtype', self::TECHNIQUE);
    }

    /**
     * Ensure that the id is a valid LAKE UID.
     *
     * @param mixed $id
     * @return boolean
     */
    public static function validateId($id)
    {
        $uid = '/^[A-Z]{2}-[0-9]+$/i';

        return preg_match($uid, $id);
    }

    public function getSubtypeDisplay()
    {
        $mapping = [
            self::CLASSIFICATION => 'classification',
            self::MATERIAL => 'material',
            self::TECHNIQUE => 'technique',
            self::STYLE => 'style',
            self::SUBJECT => 'subject',
            self::DEPARTMENT => 'department',
            self::THEME => 'theme',
        ];

        return $mapping[$this->subtype] ?? null;
    }

    /**
     * Filters the `category_terms` table by `is_category` to match `$isCategory` in model.
     * Uses the inline method for scope definition, rather than creating new classes.
     *
     * @link https://stackoverflow.com/questions/20701216/laravel-default-orderby
     *
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        // Allows querying all CategoryTerms directly
        if (!isset(static::$isCategory)) {
            return;
        }

        static::addGlobalScope('caterm', function ($builder) {
            $builder->where('is_category', '=', static::$isCategory);
        });
    }
}
