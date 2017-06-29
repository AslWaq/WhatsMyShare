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
use App\Traits\DateTrait;
use App\Traits\QuandlTrait;

class CompanySearch extends Controller
{
  use DateTrait;
  use QuandlTrait;

  public function __construct(){
    $this->middleware('auth');
  }

  public function stockSearch(){
    $results = array();

    //$queries = DB::table('tickers')->where('ticker', 'LIKE', '%'.$searchTerm.'%')
    //->orWhere('name', 'LIKE', '%'.$searchTerm.'%')->take(5)->get();
    $queries = Ticker::all();

    foreach ($queries as $query){
      array_push($results,$query->name);
    }

    $results = json_encode($results);
    return view('stockChoice', compact('results'));
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
      $date = $this -> getDateString();
      $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='.$date.'&qopts.columns=ticker,close&ticker='.$tickstring.'&api_key=JxDXY6jBDscX9-pYTiov';
      $prices = array();
      if (!$portfolio->isEmpty()){
        $ar = $this->getPrices($url);
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
        $shortAr = $this->getPrices($shortUrl);
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
      return view('user_details',compact('portfolio','namearray','prices', 'shortsArray'));
    }

    public function showByCategory (Request $request){
      $cmpnyObj = Ticker::where('category',$request->categoryChoice)->get();
      $tickstring = '';
      $category = $request->categoryChoice;
      foreach ($cmpnyObj as $tick){
        $tickstring .= $tick->ticker . ',';
      }
      $date = $this -> getDateString();
      $tickstring = substr($tickstring,0,-1);
      $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='.$date.'&qopts.columns=ticker,date,close&ticker='.$tickstring.'&api_key=JxDXY6jBDscX9-pYTiov';
      $ar = $this->getPrices($url);
      $data = array_values(array_values($ar)[0]);
      $category_closing_prices = $data[0];
      $cmpnyObj = $cmpnyObj->keyBy('ticker');
      return view('searchResults', compact('category_closing_prices','category','cmpnyObj'));
    }

    public function autocomplete (Request $request){
      $searchTerm = $request->key;
      $results = array();
      $queries = DB::table('tickers')->where('ticker', 'LIKE', '%'.$searchTerm.'%')
      ->orWhere('name', 'LIKE', '%'.$searchTerm.'%')->take(5)->get();
      foreach ($queries as $query){
        array_push($results,$query->name);
      }
      return json_encode($results);
   }


   public function companyHalfYear(Request $request){
     $startDate = Carbon::now() -> subMonths(6) -> format('Ymd');
     $endDate = Carbon::now() -> format ('Ymd');

     //$ticker = DB::table('tickers')->select('ticker')->where('ticker', '=', $request->ticker)->first();
     //$tickstring = $ticker -> ticker;

     $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date.gte='. $startDate . '&date.lte=' .
     $endDate . '&qopts.columns=date,close&ticker='.$request->ticker.'&api_key=JxDXY6jBDscX9-pYTiov';
     $jsonArray = $this->getPrices($url);
     $data = array_values(array_values($jsonArray)[0]);
     return $data[0];
   }


  public function get_price(Request $request){
    $ticker = $request->ticker;
    $date = $this->getDateString();
    $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='.$date.'&qopts.columns=ticker,close&ticker=' . $ticker .'&api_key=JxDXY6jBDscX9-pYTiov';
    $ar = $this->getPrices($url);
    $data = array_values(array_values($ar));
    return $data[0]['data'][0];
    //return array_values((array_values($data[0]))[1])->close;
  }


  public function searchByName(Request $request){
    $name = $request->textSearch;
    //return $name;
    $searchedStock = Ticker::where('name', $name)->get();
    if ($searchedStock->isEmpty()){
      $request->session()->flash('nameSearchError', 'The search query was invalid');
      return redirect('/search-stocks');
    }
    $category = null;

    $tickstring = '';
    foreach ($searchedStock as $tick){
      $tickstring .= $tick->ticker . ',';
    }
    //return $tickstring;
    $date = $this -> getDateString();
    $tickstring = substr($tickstring,0,-1);
    $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='.$date.'&qopts.columns=ticker,date,close&ticker='.$tickstring.'&api_key=JxDXY6jBDscX9-pYTiov';
    $ar = $this->getPrices($url);
    $data = array_values(array_values($ar)[0]);
    //$dataagain = array_values($data[0]);
    $category_closing_prices = $data[0];
    $cmpnyObj = $searchedStock->keyBy('ticker');
    return view('searchResults', compact('category_closing_prices','category','name','cmpnyObj'));

  }

}
