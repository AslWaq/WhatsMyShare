<?php
namespace App\Traits;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

trait QuandlTrait {
  public function getPrices($url) {
    $client = new \GuzzleHttp\Client();
    try{
      $res = $client->get(
          $url,
          ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
        );
      $contents = $res->getBody();
      return json_decode($contents,true);
    }catch(ClientException $e){
      abort(500,'Sorry, the stock price server is currently down.');
    }
  }
}
