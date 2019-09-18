<?php

namespace App;

use App\Tag;
use App\Expert;
use App\UanalyzeModel;

final class Category extends UanalyzeModel
{

    const TABLE = 'categories';
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    public function categoryProductRelation()
    {
        return $this->hasMany(CategoryProduct::class, 'category_id');
    }
}
