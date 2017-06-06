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
    $usr = Auth::user();
    $sc = array('FB', 'ALL', 'AAL');
    $scj = json_encode($sc);
    $usr->shopping_cart = $scj;
    $usr->save();
    return view('checkoutPage');
  }
  public function addToCart(Request $req){
    $usr = Auth::user();
    $sc =  $req->item;
    $c = $usr->shopping_cart;
    $cart = json_decode($c);
    //return $cart[0][1];


    array_push($cart, $sc);
    return $cart;
    $usr->shopping_cart = $cart;
    $usr->save();
    return $cart;
  }

    //
}
