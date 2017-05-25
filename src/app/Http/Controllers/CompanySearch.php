<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticker;
use Auth;
use DB;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class CompanySearch extends Controller
{
    //$request -> categoryChoice
    //placeholder returns for testing
    public function showByCategory (Request $request){
      $tickers = Ticker::where('category', $request->categoryChoice)->orderBy('name')
      ->get();
      $ticker = ($tickers->first())->ticker;
      $url = 'https://www.quandl.com/api/v3/datasets/WIKI/'.$ticker .'/data.json';
      $client = new \GuzzleHttp\Client();
      $res = $client->get(
          $url,
          ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
      );

      $contents = $res->getBody()->getContents();
      //var_dump(json_decode($contents));
            return $contents;
    }

    public function showBySearch (Request $request){
      $searchTerm = $request->textSearch;
      $tickers = Ticker::where('ticker', 'LIKE', "%$searchTerm%")
      ->orWhere('name', 'LIKE', "%$searchTerm%")->get();

      if (!$tickers->isEmpty()){
        $ticker = ($tickers->first())->ticker;
        return view('quandl');
      } else{
        return "error";
      }


    }

}
