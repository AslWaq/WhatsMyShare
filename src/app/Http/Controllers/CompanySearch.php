<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use App\Ticker;
use Auth;
use App\User;
use App\Short;
use DB;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class CompanySearch extends Controller
{

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
      }

      //2017 holiday check
      if($dt->isSameDay(Carbon::createFromDate(2017,05,29))){ //memorial day
        $dt = $dt->subDays(3);
      }elseif ($dt->isSameDay(Carbon::createFromDate(2017,07,04))){ //independence day
        $dt = $dt->subDays(1);
      }
      return $dt->format('Ymd');
    }

    public function dashboard(){
      $usr = Auth::user()->id;
      $user = Auth::user();
      $portfolio = $user->stocks;
      $namearray = array();
      $tickstring = '';
      if (!$portfolio->isEmpty()){
        foreach ($portfolio as $stock){
          $tick = Ticker::where('ticker',$stock->stock_ticker)->first();
          array_push($namearray,$tick->name);
          $tickstring .= $stock->stock_ticker . ',';
        }
        $tickstring = substr($tickstring,0,-1);
      }
      //return $tickstring;
      $date = $this -> getDateString();
      $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='.$date.'&qopts.columns=ticker,close&ticker='.$tickstring.'&api_key=JxDXY6jBDscX9-pYTiov';
      $investValue = 0;
      $prices = array();
      $client = new \GuzzleHttp\Client();
      //get the portfolio and the current prices
      if (!$portfolio->isEmpty()){
        $res = $client->get(
            $url,
            ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
        );
        $contents = $res->getBody();
        $ar = json_decode($contents,true);
        $data = array_values(array_values($ar)[0]);
        $closing_prices = $data[0];
        //create a key->value array for ticker->price
        $keys = array();
        $values = array();
        for ($x=0; $x < count($closing_prices); $x++){
          array_push($keys,$closing_prices[$x][0]);
          array_push($values,$closing_prices[$x][1]);
        }
        $prices = array_combine($keys,$values);
        //end method for creating key->value array for ticker price
        $investValue = 0;
        foreach($portfolio as $stock){
          $investValue += $stock->shares * ($prices{$stock->stock_ticker});
        }
      }

      $shorts = $user->shorts;
      $shortKeys = array();
      $shortValues = array();
      $shortString = '';
      $shortsArray = array();
      if (!$shorts->isEmpty()){
        foreach ($shorts as $short){
          $shortString .= $short->stock_ticker . ',';
        }
        $shortString = substr($shortString,0,-1);
        $shortUrl = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='
        .$date.'&qopts.columns=ticker,close&ticker='.$shortString.'&api_key=JxDXY6jBDscX9-pYTiov';
        $response = $client->get(
            $shortUrl,
            ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
        );
        $contents = $response->getBody();
        $shortAr = json_decode($contents,true);
        $shortData = array_values(array_values($shortAr)[0]);
        $shortPrices = $shortData[0];

        for ($y=0; $y < count($shortPrices); $y++){
          $short = Short::where('stock_ticker',$shortPrices[$y][0])->first();
          $gainOrLoss = (($short->shares * $shortPrices[$y][1]) - ($short->shares * $short->initial_price))/($short->shares * $short->initial_price);
          $gainOrLoss = round(-1 * 100 * $gainOrLoss);
          $shortKeys[]=$shortPrices[$y][0];
          $shortValues[]=array($shortPrices[$y][1],$gainOrLoss);
        }
        $shortsArray = array_combine($shortKeys,$shortValues);
      }


      //return $prices{'AMZN'};




      //return $portfolio;
      return view('user_details',compact('portfolio','namearray','prices','investValue', 'shortsArray'));
    }//

    public function showByCategory (Request $request){
      $cmpnyObj = Ticker::where('category',$request->categoryChoice)->get();
      //$tickers = DB::table('tickers')->select('ticker')->where('category', '=', $request->categoryChoice)->get();
      $tickstring = '';
      foreach ($cmpnyObj as $tick){
        $tickstring .= $tick->ticker . ',';
      }
      //return $tickstring;
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
      $cmpnyObj = $cmpnyObj->keyBy('ticker');
      //
      //return $cmpnyObj;
      return view('searchResults', compact('category_closing_prices','cmpnyObj'));//
      //return $category_closing_prices;
    }

    public function autocomplete (Request $request){
      $searchTerm = $request->key;
      //$search_param = "{$searchTerm}%";
      $results = array();

      $queries = DB::table('tickers')->where('ticker', 'LIKE', '%'.$searchTerm.'%')
      ->orWhere('name', 'LIKE', '%'.$searchTerm.'%')->take(5)->get();

      foreach ($queries as $query){
        array_push($results,$query->name);
      }

      return json_encode($results);


      // if (!$tickers->isEmpty()){
      //   $ticker = ($tickers->first())->ticker;
      //   return view('quandl');
      // } else{
      //   return "error";
      // }
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

  public function get_price(Request $request){
    $ticker = $request->ticker;
    $date = $this->getDateString();
    $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='.$date.'&qopts.columns=ticker,close&ticker=' . $ticker .'&api_key=JxDXY6jBDscX9-pYTiov';
    $client = new \GuzzleHttp\Client();
    $res = $client->get(
      $url,
      ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
    );

    $contents = $res->getBody();
    $ar = json_decode($contents, true);
    $data = array_values(array_values($ar));
    return $data[0]['data'][0];
    //return array_values((array_values($data[0]))[1])->close;
  }

  public function testuseroutput(){
    $id = Auth::user()->id;
    return User::find($id)->stocks;
  }

  public function dailyInvestScore(){
    $stocks = DB::table('portfolio')->distinct()->select('stock_ticker')->get();
    $tickstring = '';
    foreach ($stocks as $tick){
      $tickstring .= $tick->stock_ticker . ',';
    }
    $date = $this->getDateString();
    $tickstring = substr($tickstring,0,-1);

    $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='.$date.
    '&qopts.columns=ticker,close&ticker='.$tickstring.'&api_key=JxDXY6jBDscX9-pYTiov';

    $client = new \GuzzleHttp\Client();
    $res = $client->get(
        $url,
        ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
    );
    $contents = $res->getBody();
    $ar = json_decode($contents, true);
    $ar2 = array_values(array_values($ar)[0]);
    $data = $ar2[0];
    $dt = Carbon::now()->format('Y-m-d');

    $users = User::all();
    foreach ($users as $user){
      $total = 0;
      if (!$user->stocks()->get() == null){
        for ($x=0; $x<count($data); $x++){
          $stock = $user->stocks()->where('stock_ticker', $data[$x][0])->first();
          if (!$stock == null){
            $total += ($stock->shares * $data[$x][1]);
          }
        }
        $user->invest_score = ($user->cash) + $total;
        $user->save();
        DB::table('scores')->insert([$user->id,$user->invest_score,$dt]);
      }else{
        $user->invest_score = $user->cash;
        $user->save();
        DB::table('scores')->insert([$user->id,$user->invest_score,$dt]);
      }
    }
  }
  public function searchByName(Request $request){
    $name = $request->textSearch;
    //return $name;
    $searchedStock = Ticker::where('name', $name)->first();
    //return $searchedStock;
    $cat = Ticker::where('category',$searchedStock->category)->get();

    $tickstring = '';
    foreach ($cat as $tick){
      $tickstring .= $tick->ticker . ',';
    }
    //return $tickstring;
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
    $cmpnyObj = $cat->keyBy('ticker');
    return view('searchResults', compact('category_closing_prices','cmpnyObj'));

  }

}
