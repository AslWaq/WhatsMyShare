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

      $ar = json_decode($request->order);

      $user = User::find(Auth::user()->id);

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
        
        $cartArray = json_decode($user->shopping_cart);
        for ($x=0; $x < count($cartArray); $x++){
          if ($cartArray[$x][0] == $ar[0]){
            $y = $x;
          }
        }
        unset($cartArray[$y]);
        $cart = json_encode($cartArray);
        $user->shopping_cart = $cart;
        $user->save();
      }
      return "hooray!";
      //return view('dashboard');
  }

  public function sellStock(Request $request){
    $user = User::find(Auth::user()->id);
    $ar = json_decode($request->stockChoice);

    $stock = Stock::where('user_id', '=', $user->id)->where('stock_ticker','=',$ar[0])->first();
    if ($stock->shares < $ar[1]){//if trying to sell more than you own
      return "impossible";
    }elseif($stock->shares == $ar[1]){
      $user->cash += ($ar[1] * $ar[2]);
      $user->invest_score += ($ar[1] * $ar[2]);
      $user->save();
      $stock->delete();
    }else{
      $stock->shares -= $ar[1];
      $stock->price = $ar[2];
      $stock->initial_val = ($stock->shares) * ($stock->price);
      $stock->save();

      $user->cash += ($ar[1] * $ar[2]);
      $user->invest_score += ($ar[1] * $ar[2]);
      $user->save();
    }
  }
}
