<?php

namespace App;

use App\UanalyzeModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class Portfolio extends UanalyzeModel
{
	use SoftDeletes;
	protected $hidden = ['deleted_at'];
	protected $fillable = [
        'stock_code', 'user_id', 'laboratory_id', 'single_id', 'cheap_price', 'expensive_price'
    ];
    public function transactions()
    {
        return $this->hasMany('App\PortfolioTransaction');
    }
}
