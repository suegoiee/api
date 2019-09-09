<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationMessage extends UanalyzeModel
{
	protected $table="notification_messages";
    protected $fillable = [
        'title','content','user_ids','send_email','send_notice','type','product_ids', 'expired_user','non_expired_user'
    ];
}
