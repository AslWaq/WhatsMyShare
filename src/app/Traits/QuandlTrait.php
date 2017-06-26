<?php
namespace App\Traits;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

trait QuandlTrait {
  public function getPrices($url) {
    $client = new \GuzzleHttp\Client();
    $res = $client->get(
        $url,
        ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
      );
    $contents = $res->getBody();
    return json_decode($contents,true);
  }
}
