<?php

namespace App;

use App\Admin;
use App\UanalyzeModel;
use App\Backend_functions;

class Role extends UanalyzeModel
{
    const TABLE = 'role';

    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    protected $fillable=['name'];
    
    function permissions(){
        return $this->belongsToMany(Backend_functions::class, 'role_functions', 'role_id', 'function_id');
    }
    
    function users(){
        return $this->hasMany(Admin::class, 'auth');
    }
}
