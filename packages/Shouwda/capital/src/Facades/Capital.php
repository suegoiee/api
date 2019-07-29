<?php
namespace Shouwda\Capital\Facades;
use Illuminate\Support\Facades\Facade;
class Capital extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'capital';
    }
}