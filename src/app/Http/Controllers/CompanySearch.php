<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticker;
use Auth;
use DB;

class CompanySearch extends Controller
{
    //$request -> categoryChoice
    //placeholder returns for testing
    public function showByCategory (Request $request){
      $tickers = Ticker::where('category', $request->categoryChoice)->orderBy('name')
      ->get();
      return $tickers;
    }

    public function showBySearch (Request $request){
      $searchTerm = $request->textSearch;
      $tickers = Ticker::where('ticker', 'LIKE', "%$searchTerm%")
      ->orWhere('name', 'LIKE', "%$searchTerm%")->get();

      if (!$tickers->isEmpty()){
        return $tickers;
      } else{
        return "error";
      }


    }

}
