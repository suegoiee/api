<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class Avatar extends UanalyzeModel
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'path','type'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
