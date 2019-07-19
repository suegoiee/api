<?php
namespace Shouwda\Capital;
use Illuminate\Support\ServiceProvider;
class Capital
{
 	protected $capital;
    public function __construct()
    {
        $capital = new stdClass();
        $capital->capitalServiceURL = config('capital.CapitalServiceURL');
    }
    protected function checkout($data)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->request('post',$capital->capitalServiceURL,[
                ],
                'form_params' => $data,
            ]);
        $response_data = json_decode((string) $response->getBody(), true);
        return $response_data;
    }
}