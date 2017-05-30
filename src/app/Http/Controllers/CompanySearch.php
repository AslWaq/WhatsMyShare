<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
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
        $dt = $dt->subDays(1);
      }elseif ($dt->dayOfWeek == Carbon::SUNDAY){
        $dt = $dt->subDays(2);
      }elseif ($dt->dayOfWeek == Carbon::MONDAY){
        $dt = $dt->subDays(3);
      }else{
        $dt->subDays(1);
<<<<<<< HEAD
      }if ($dt->isSameDay(Carbon::createFromDate(2017,05,29))){ //memorial day
=======
      }

      //2017 holiday check
      if($dt->isSameDay(Carbon::createFromDate(2017,05,29))){ //memorial day
>>>>>>> 4dc1d6d546bef0b754295a61f182e020c506503f
        $dt = $dt->subDays(3);
      }elseif ($dt->isSameDay(Carbon::createFromDate(2017,07,04))){ //independence day
        $dt = $dt->subDays(1);
      }
      return $dt->format('Ymd');
    }

    public function showByCategory (Request $request){

      $tickers = DB::table('tickers')->select('ticker')->where('category', '=', $request->categoryChoice)->get();
      $tickstring = '';
      foreach ($tickers as $tick){
        $tickstring .= $tick->ticker . ',';
      }

      $date = $this -> getDateString();
      $tickstring = substr($tickstring,0,-1);
      $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='.$date.'&qopts.columns=ticker,date,close&ticker='.$tickstring.'&api_key=JxDXY6jBDscX9-pYTiov';

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
      return view('searchResults', compact('category_closing_prices'));//
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

    public function companyDescription (Request $request){
      $ticker = Ticker::find($request->ticker);
      $ticklink = $ticker -> link;
      if ($ticklink == 'N/A'){
        return "Company information unavailable.";
      }
      $wikipage = substr($ticklink,30);

       //makes tickers list into a string for api call
      $wikistring='https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$wikipage;

      $client = new \GuzzleHttp\Client();
      $response = $client->get($wikistring);
      $contents = $res2->getBody();
      $data = json_decode($contents,true);
      return array_values($data['query']['pages'])[0]['extract'];
    }
    public function ajaxEg(Request $req){
      $ticker = $req->ticker;
      $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date.gte=20170101&qopts.columns=ticker,date,close&ticker=' . $ticker .'&api_key=JxDXY6jBDscX9-pYTiov';
      $client = new \GuzzleHttp\Client();
      $res = $client->get(
          $url,
          ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
      );

      $contents = $res->getBody();
      $ar = json_decode($contents, true);
      $data = array_values(array_values($ar));
      return (array_values($data[0]))[0];
   }

   public function companyHalfYear(Request $request){
     $startDate = Carbon::now() -> subMonths(6) -> format('Ymd');
     $endDate = Carbon::now() -> format ('Ymd');

     //$ticker = DB::table('tickers')->select('ticker')->where('ticker', '=', $request->ticker)->first();
     //$tickstring = $ticker -> ticker;

     $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date.gte='. $startDate . '&date.lte=' .
     $endDate . '&qopts.columns=date,close&ticker='.$request->ticker.'&api_key=JxDXY6jBDscX9-pYTiov';

     $client = new \GuzzleHttp\Client();
     $response = $client->get(
         $url,
         ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
     );
     $contents = $response->getBody();
     $jsonArray = json_decode($contents, true);
     $data = array_values(array_values($jsonArray)[0]);
     return $data[0];
   }

   public function companyCustomTime(Request $request){
     $dt = DateTime::createFromDate('m/d/Y',$request -> dateStart);
     $startDate = $dt -> format('Ymd');
     $dt1 = DateTime::createFromDate('m/d/Y', $request -> dateEnd);
     $endDate = $dt1 -> format ('Ymd');

     $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date.gte='. $startDate . '&date.lte=' .
     $endDate . '&qopts.columns=date,close&ticker='.$request->ticker.'&api_key=JxDXY6jBDscX9-pYTiov';

     $client = new \GuzzleHttp\Client();
     $response = $client->get(
         $url,
         ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
     );
     $contents = $response->getBody();
     $jsonArray = json_decode($contents, true);
     $data = array_values(array_values($jsonArray)[0]);
     return $data[0];
}

}
