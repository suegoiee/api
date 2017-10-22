<?php
namespace Shouwda\Ecpay\Facades;
use Illuminate\Support\Facades\Facade;
class Ecpay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ecpay';
    }
}