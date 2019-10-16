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

    public function user()
    {
        return $this->hasOneThrough('App/User', 'App/Order', 'user_id');
    }

    /*public function user()
    {
        return $this->hasOne('App/Order', 'user_id');
    }*/
}
