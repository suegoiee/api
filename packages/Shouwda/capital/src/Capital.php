<?php
namespace Shouwda\Capital;
use Illuminate\Support\ServiceProvider;
class Capital
{
 	protected $capital;
    public function __construct()
    {
        $this->capital = new \stdClass();
        $this->capital->capitalServiceURL = config('capital.CapitalServiceURL');
    }
    public function checkout($data)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->request('post', $this->capital->capitalServiceURL,[],
                [
                    'form_params' => $data,
                ]);
        return $response;
        //$response_data = json_decode((string) $response->getBody(), true);
        return $response_data;
    }
}