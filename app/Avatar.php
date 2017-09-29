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
    protected $appends=[
    	'url',
    ];
    public function imageable()
    {
        return $this->morphTo();
    }
    public function getUrlAttribute(){
    	return url('storage/'.$this->path);
    }
}
