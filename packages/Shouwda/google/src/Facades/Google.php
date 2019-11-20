<?php
namespace Shouwda\Google\Facades;
use Illuminate\Support\Facades\Facade;
class Google extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'google';
    }
}