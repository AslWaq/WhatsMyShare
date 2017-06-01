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
    //
}
