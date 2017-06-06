<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Stock;
use App\Ticker;

class PortfolioTransactionController extends Controller
{
    public function buyStocks(Request $request){
      // $ar = array(
      //   array("FB",10,153.63),
      //   array("MSFT",20,72.28),
      //   array("WMT",10,80.26)
      // );
      $ar = json_decode($request->order);
      $user = User::find(Auth::user()->id);
      // $arlength=count($ar);
      //
      // $transTotal = $ar[0][1];


      $transTotal = ( $ar[1] * $ar[2]);

      if ($transTotal > Auth::user()->cash){
        return view('dashboard');
      }else{

          $stock = Stock::where('user_id', '=', Auth::user()->id)->where('stock_ticker', '=', $ar[0])->first();
          if ($stock == null){ // if user does not own the stock yet
            $stock = new Stock;
            $stock->user_id = Auth::user()->id;
            $stock->stock_ticker = $ar[0];
            $stock->shares = $ar[1];
            $stock->price = $ar[2];
            $stock->initial_val = ($ar[1] * $ar[2]);
            $stock->save();
          }else{
            $stock->shares += $ar[1];
            $stock->price = $ar[2];
            $stock->initial_val = ($stock->shares) * ($stock->price);
            $stock->save();
          }

        $user->cash -= $transTotal;
        $user->save();
      }
      return "hooray!";
      //return view('dashboard');
    }
}
