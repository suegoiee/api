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
        $query = json_encode($data);
        $ch = curl_init($this->capital->capitalServiceURL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE => 1,
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        return json_decode($response, true);
        /*$http = new \GuzzleHttp\Client;
        $response = $http->request('post', $this->capital->capitalServiceURL,
                [
                    'form_params' => $data,
                ]);
        return $response;

        $response_data = json_decode((string) $response->getBody(), true);
        return $response_data;*/
    }
}