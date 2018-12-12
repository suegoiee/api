<?php
namespace Shouwda\EcpayInvoice\Facades;
use Illuminate\Support\Facades\Facade;
class EcpayInvoice extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ecpayInvoice';
    }
}