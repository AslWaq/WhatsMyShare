<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use SammyK;
use App\User;
use App\Http\Controllers\Controller;
use Auth;
use Session;

class TransactionController extends Controller
{
    public function dashboard(){
      return view('fb_login');
    }//

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

      Auth::login($user);
      return redirect('/');

    }
}
