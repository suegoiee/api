<?php

namespace App;

use App\UanalyzeModel;

class Plan extends UanalyzeModel
{

    const TABLE = 'plans';

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    /**
     * {@inheritdoc}
     */
	protected $fillable=['name','price','introduction','active'];
}
