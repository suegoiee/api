<?php
namespace Uanalyze\Mitake;

class Mitake
{
    public function send($addr, $message, $clientID = null)
    {
        $url = sprintf(config('mitake.SmGateway'), $addr, urlencode($message), $clientID);
        var_dump(file_get_contents($url));
    }
}