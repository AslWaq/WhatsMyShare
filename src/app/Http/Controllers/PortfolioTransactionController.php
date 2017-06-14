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
      //invest score change = (new price * shares sold) - (old price * shares sold)
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
    //return view('dashboard'); //send back message saying you already have this stock shorted
  }



    //return view('dashboard');
  }

  //pay the Short back
  public function buyShort(Request $request){
    //buy the shares, lose cash. but invest score may still go up if you bought at lower price than you shorted
    $user = Auth::user();
    $ar = json_decode($request->order);
    $short = Short::where('user_id',$user->id)->where('stock_ticker',$ar[0])->first();
    $user->cash -= (($short->shares) * $ar[1]);
    $user->invest_score += (($short->shares) * $ar[1]) - (($short->shares) * ($short->initial_price));
    $user->save();
    $short->delete();



  }
  public function leaderboard(){
    $users = User::orderBy('invest_score', 'desc')->get();
    //$user = Auth::user();
    $curUser = $users->first();
    $isFriend = false;
    $isFBFriend = false;
    $friends = Auth::user()->friends()->orderBy('invest_score','desc')->get();
    if ($friends != null){
      foreach ($friends as $friend){
        if ($friend->id == $curUser->id){
          $isFriend = true;
          if ($friend->pivot->facebook_friend == true){
            $isFBFriend = true;
          }
        }
      }
    }
    $fflag = false;
    return view('leaderboard', compact('users', 'curUser', 'isFriend', 'isFBFriend', 'fflag'));
  }
  public function friends(){
    $users = Auth::user()->friends()->orderBy('invest_score','desc')->get();
    //$user = Auth::user();
    $curUser = $users->first();
    $isFBFriend = false;
    $isFriend = false;
    foreach ($users as $friend){
      if ($friend->id == $curUser->id){
        $isFriend = true;
        if ($friend->pivot->facebook_friend){
          $isFBFriend = true;
        }
      }
    }

    //return $user->friends;
    //return $users;
    $fflag = true;
    return view('leaderboard', compact('users', 'curUser', 'isFriend', 'isFBFriend', 'fflag'));
  }
}
