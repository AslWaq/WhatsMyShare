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

class FBLoginController extends Controller
{


    public function fBRedirect(){
      $fb = app(SammyK\LaravelFacebookSdk\LaravelFacebookSdk::class);

      $loginUrl = $fb->getRedirectLoginHelper()->getLoginUrl('http://localhost:8000/fbcb',['email', 'user_friends']);
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
      Session::put('fb_user_access_token', (string) $token);
      $response = $fb->get('/me?fields=friends');
      $friends = json_decode($response->getGraphObject()['friends']);

      $facebookFriendsCount = count($friends);
      if ($user->cash == null){
        $user->cash = 30000;
        $user->invest_score = 30000;
      }
      if($user->shopping_cart == null){
        $user->shopping_cart = json_encode(array());
      }
      if ($facebookFriendsCount > 0){
        if ($user->friends->isEmpty()){
          for ($i = 0; $i < $facebookFriendsCount; $i++){
            $fbFriend = User::where('facebook_user_id', $friends[$i]->id)->first();
            if ($fbFriend){
            $fID = $fbFriend->id;
            $user->addFriend($fID, true);
            }
          }
        }else{
          for ($i = 0;$i < $facebookFriendsCount; $i++){
            $user = User::find($user->id);
            $larFriends = $user->friends;
            $dontBother = true;
            foreach ($larFriends as $larFriend){
              if (!$larFriend->facebook_user_id){
                continue;
              }elseif ($larFriend->facebook_user_id == $friends[$i]->id){
                if($larFriend->pivot->facebook_friend == false){
                  $user->removeFriend($larFriend->id);
                  $user = User::find($user->id);
                  $user->addFriend($larFriend->id,true);
                }else{
                  continue;
                }
              }else {
                $dontBother = false;
              }
            }
            if($dontBother == false){
              $fbFriend = User::where('facebook_user_id', $friends[$i]->id)->first();
              if($fbFriend){
                $user->addFriend($fbFriend->id, true);
              }
            }
          }
          // foreach ($user->friends as $friend){
          //   $leaveThisPersonAlone = true;
          //   for ($i = 0;$i < $facebookFriendsCount; $i++){
          //     if (!$friend->facebook_user_id){
          //       continue;
          //     }
          //     elseif ($friend->facebook_user_id == $friends[$i]->id){
          //       continue;
          //     }
          //     else{
          //       $leaveThisPersonAlone = false;
          //     }
          //   }
          //   if (!$leaveThisPersonAlone){
          //     $user->removeFriend($friend->id);
          //   }
          // }
        }
      }else{
        if(!$user->friends->isEmpty()){
          foreach ($user->friends as $friend){
            if ($friend->pivot->facebook_friend == true){
              $user->removeFriend($friend->id);
            }
          }
        }
      }
      $user->save();
      Auth::login($user);
      return redirect('/dashboard');

    }
}
