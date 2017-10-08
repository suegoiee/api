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
    protected $hidden=[
        'id','type','path', 'imageable_id', 'imageable_type', 'created_at', 'updated_at', 'deleted_at'  
    ];
    public function imageable()
    {
        return $this->morphTo();
    }
    public function getUrlAttribute(){
    	return url('storage/'.$this->path);
    }
}
