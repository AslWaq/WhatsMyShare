<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use SammyK;
use App\User;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use App\Stock;
use App\Ticker;

class TransactionController extends Controller
{


    public function redrect(){
      $fb = app(SammyK\LaravelFacebookSdk\LaravelFacebookSdk::class);

      $loginUrl = $fb->getRedirectLoginHelper()->getLoginUrl('http://localhost:8000/fbcb',['email']);
      return redirect($loginUrl);
    }

    public function fbCallback(){
      $fb = app(SammyK\LaravelFacebookSdk\LaravelFacebookSdk::class);
      try{
        $token = $fb->getRedirectLoginHelper()->getAccessToken();

      }
      catch(Facebook\Exceptions\FacebookSDKException $e){
        dd($e->getMessage());

      }
      if(!$token){
        return redirect('/login');
      }
      if(! $token->isLongLived()){
        $oauth_client = $fb->getOAuth2Client();
        try{
          $token = $oauth_client->getLongLivedAccessToken($token);
        }catch(Facebook\Exceptions\FacebookSDKException $e){
          dd($e->getMessage());
        }
      }
      $fb->setDefaultAccessToken($token);
      Session::put('fb_user_access_token', (string) $token);
      try{
        $response = $fb->get('/me?fields=id, name, email');
      }catch(Facebook\Exceptions\FacebookSDKException $e){
        dd($e->getMessage());
      }

      $facebook_user = $response->getGraphUser();
      $user = User::createOrUpdateGraphNode($facebook_user);
      if ($user->cash == null){
        $user->cash = 30000;
        $user->invest_score = 30000;
      }
      if($user->shopping_cart == null){
        $user->shopping_cart = json_encode(array());
      }
      Auth::login($user);
      $user->save();
      return redirect('/');

    }


    public function stockSearch(){
      $results = array();

      //$queries = DB::table('tickers')->where('ticker', 'LIKE', '%'.$searchTerm.'%')
      //->orWhere('name', 'LIKE', '%'.$searchTerm.'%')->take(5)->get();
      $queries = Ticker::all();

      foreach ($queries as $query){
        array_push($results,$query->name);
      }

      $results = json_encode($results);
      return view('stockChoice', compact('results'));
    }
    public function usrProf(Request $req){
      $user = Auth::user();
      $curUser = User::find($req->id);
      $users = User::orderBy('invest_score', 'desc')->get();
      $isFriend = false;
      $friends = $user->friends;
      if ($friends != null){
        foreach ($friends as $friend){
          if ($friend->id == $curUser->id){
            $isFriend = true;
          }
        }
      }
      //return $users;
      $fflag = false;
      return view('leaderboard', compact('users', 'curUser','isFriend', 'fflag'));
      //$port = user
    }

    public function friendProf(Request $req){
      $user = Auth::user();
      $curUser = User::find($req->id);
      $users = $user->friends;
      $isFriend = false;
      foreach ($users as $friend){
        if ($friend->id == $curUser->id){
          $isFriend = true;
        }
      }
      //return $users;

      $fflag = true;
      return view('leaderboard', compact('users', 'curUser', 'isFriend', 'fflag'));
      //$port = user
    }
}
