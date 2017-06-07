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

    //
}
