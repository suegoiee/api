<?php
namespace Uanalyze\Mitake\Facades;
use Illuminate\Support\Facades\Facade;

class Mitake extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mitake';
    }
}