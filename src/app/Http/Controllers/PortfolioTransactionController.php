<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Stock;
use App\Ticker;
use App\Short;
use Carbon\Carbon;


class PortfolioTransactionController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

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

      //delete from cart
      $cartArray = json_decode($user->shopping_cart);
      for ($x=0; $x < count($cartArray); $x++){
        if (json_decode($cartArray[$x])[0] == $ar[0]){
          $y = $x;
        }
      }
      unset($cartArray[$y]);
      $cart = json_encode(array_values($cartArray));
      $user->shopping_cart = $cart;
      $user->save();
      }
      return "hooray!";
  }

  public function sellStocks(Request $request){
    $user = User::find(Auth::user()->id);
    $ar = json_decode($request->order);

    $stock = Stock::where('user_id', '=', $user->id)->where('stock_ticker','=',$ar[0])->first();

    if ($stock->shares < $ar[1]){//if trying to sell more than you own
      return "impossible";
    }elseif($stock->shares == $ar[1]){
      $user->cash += ($ar[1] * $ar[2]);
      $user->save();
      $stock->delete();
      $request->session()->flash('transMsg', 'You sold '. $ar[0] . ' shares. For a total of $' . $ar[1] * $ar[2]);
    }else{
      $stock->shares -= $ar[1];
      $stock->price = $ar[2];
      $stock->initial_val = ($stock->shares) * ($stock->price);
      $stock->save();

      $user->cash += ($ar[1] * $ar[2]);
      $user->save();
      $request->session()->flash('transMsg', 'You sold '. $ar[0] . ' shares. For a total of $' . $ar[1] * $ar[2]);

    }
  }

  //Short Stock
  public function getShort(Request $request){
    $user = Auth::user();
    $dt = Carbon::now()->format('Y-m-d');
    $ar = json_decode($request->order);
    if ($datshort = Short::where('user_id',$user->id)->where('stock_ticker',$ar[0])->first() == null){
      $short = new Short;
      $short->user_id = Auth::user()->id;
      $short->stock_ticker = $ar[0];
      $short->shares = $ar[1];
      $short->initial_price = $ar[2];
      $short->shorted_at = $dt;
      $short->save();

      $cartArray = json_decode($user->shopping_cart);
      for ($x=0; $x < count($cartArray); $x++){
        if (json_decode($cartArray[$x])[0] == $ar[0]){
          $y = $x;
        }
      }
      unset($cartArray[$y]);
      $cart = json_encode(array_values($cartArray));
      $user->shopping_cart = $cart;
      $user->save();
    }else{
      $request->session()->flash('shortErrorMsg', 'You already have shorted '.
      $ar[0] . '. Please pay back the shorted stock if you would like to short
      this stock again.');
    }
  }

  //pay the Short back
  public function buyShort(Request $request){
    //buy the shares, lose cash. but invest score may still go up if you bought at lower price than you shorted
    $user = Auth::user();
    $short = Short::where('user_id',$user->id)->where('stock_ticker',$request->ticker)->first();
    //negative if gain (will be added to invest score though) and positive if loss
    $gainOrLoss = (($short->shares) * $request->price) - (($short->shares) * ($short->initial_price));
    $user->cash -= $gainOrLoss;
    $user->invest_score -= $gainOrLoss;
    $user->save();
    $short->delete();
    $request->session()->flash('transMsg', 'You paid back '. $request->ticker . ' shorts. Your gain (or loss) from these shorted stocks is: $'. (-1 * $gainOrLoss));
    return redirect('/dashboard');
  }

}
