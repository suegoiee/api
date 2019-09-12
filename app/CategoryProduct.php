<?php

namespace App;

use App\Tag;
use App\Expert;
use App\UanalyzeModel;

final class CategoryProduct extends UanalyzeModel
{

    const TABLE = 'category_product';

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'product_id',
        'category_id',
    ];
}
