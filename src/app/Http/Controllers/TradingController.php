<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class TradingController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
  public function viewCart(){
    //$usrCartJson = Auth::user()->shopping_cart;
    //$sc = array('FB', 'ALL', 'AAL');
    //$scj = json_encode($sc);
    //$usr->shopping_cart = $scj;
    //$usr->save();
    return view('checkoutPage');
  }
  public function addToCart(Request $req){
    $usr = Auth::user();
    $addedItem = ($req->item);
    //$item = json_encode($sc);
    $usrCartJson = $usr->shopping_cart;
    $usrCartArray = json_decode($usrCartJson);
    //return $cart;
    if (!in_array($addedItem, $usrCartArray)){
      array_push($usrCartArray, $addedItem);
    }
    $usrCartJson = json_encode($usrCartArray);
    $usr->shopping_cart = $usrCartJson;
    $usr->save();
    return $usr->shopping_cart;

  }
  public function delItem(Request $req){
    $user = Auth::user();
    $ticker = $req->item;
    $y = null;
    $cartArray = json_decode($user->shopping_cart);
    for ($x=0; $x < count($cartArray); $x++){
      if (json_decode($cartArray[$x])[0] == $ticker){
        $y = $x;
      }
    }
    unset($cartArray[$y]);
    $cart = json_encode(array_values($cartArray));
    $user->shopping_cart = $cart;
    $user->save();
    return('hurray');
  }

    //
}
