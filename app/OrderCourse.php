<?php

namespace App;

use App\User;
use App\Order;
use App\UanalyzeModel;

final class OrderCourse extends UanalyzeModel
{
    const TABLE = 'order_course';
    /**
     * {@inheritdoc}
     */
    protected $table = self::TABLE;

    public function students()
    {
        return $this->hasMany('App/Order', 'order_id');
    }

    /*public function user()
    {
        return $this->hasOne('App/Order', 'user_id');
    }*/
}
