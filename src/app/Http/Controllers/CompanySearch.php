<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Ticker;
use Auth;
use DB;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class CompanySearch extends Controller
{
    //$request -> categoryChoice
    //placeholder returns for testing
    public function getDateString(){
      $dt = Carbon::now();
      //check if saturday or sunday and move back to friday
      if ($dt->dayOfWeek == Carbon::SATURDAY){
        $dt = Carbon::yesterday();
      }elseif ($dt->dayOfWeek == Carbon::SUNDAY){
        $dt = $dt->subDays(2);
      }elseif ($dt->dayOfWeek == Carbon::MONDAY){
        $dt = $dt->subDays(3);
      }
      elseif ($dt->isSameDay(Carbon::createFromDate(2017,05,29))){ //memorial day
        $dt = $dt->subDays(3);
      }elseif ($dt->isSameDay(Carbon::createFromDate(2017,07,04))){ //independence day
        $dt = $dt->subDays(1);
      }else{
        return $dt->subDays(1)->format('Ymd');
      }
    }


      $tickers = DB::table('tickers')->select('ticker')->where('category', '=', $request->categoryChoice)->get();
      $tickstring = '';
      foreach ($tickers as $tick){
        $tickstring .= $tick->ticker . ',';
      } //makes tickers list into a string for api call
      $tickstring = substr($tickstring,0,-1);
      $date = $this -> getDateString();
      $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='. $date . '&qopts.columns=ticker,date,close&ticker='.$tickstring.'&api_key=JxDXY6jBDscX9-pYTiov';
      $client = new \GuzzleHttp\Client();
      $res = $client->get(
          $url,
          ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
      );

      $contents = $res->getBody();
      $ar = json_decode($contents, true);
      $data = array_values(array_values($ar)[0]);
      //$dataagain = array_values($data[0]);
      $category_closing_prices = $data[0];
      return $category_closing_prices;

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
