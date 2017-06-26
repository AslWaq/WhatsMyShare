<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class TradingController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
  public function viewCart(){
    return view('checkoutPage');
  }
  public function addToCart(Request $req){
    $usr = Auth::user();
    $addedItem = ($req->item);
    $usrCartJson = $usr->shopping_cart;
    $usrCartArray = json_decode($usrCartJson);
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

  public function friendorFoe(Request $request){
    // should be a boolean value true or false
    $follow = $request->status;
    $user = Auth::user();
    if(strcmp($follow, "Follow")==0){
      $user->addFriend($request->id,false);
      return "Unfollow";
    }else{
      $user->removeFriend($request->id);
      return "Follow";
    }
  }
}
