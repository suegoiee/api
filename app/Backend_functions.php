<?php

namespace App;

use App\UanalyzeModel;

class Backend_functions extends UanalyzeModel
{
    const TABLE = 'backend_functions';

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

	protected $fillable=['name', 'controller', 'method'];
}
