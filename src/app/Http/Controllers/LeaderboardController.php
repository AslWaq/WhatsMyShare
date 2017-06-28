<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Stock;
use App\Ticker;
use App\Short;
use Carbon\Carbon;


class LeaderboardController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
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

  public function usrProf(Request $req){
    $user = Auth::user();
    $curUser = User::find($req->id);
    $users = User::orderBy('invest_score', 'desc')->get();
    $isFriend = false;
    $isFBFriend = false;
    $friends = $user->friends()->orderBy('invest_score','desc')->get();
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
    //return $users;
    $fflag = false;
    return view('leaderboard', compact('users', 'curUser','isFriend', 'isFBFriend', 'fflag'));
    //$port = user
  }

  public function friendProf(Request $req){
    $user = Auth::user();
    $curUser = User::find($req->id);
    $users = $user->friends()->orderBy('invest_score','desc')->get();
    $isFriend = false;
    $isFBFriend = false;
    foreach ($users as $friend){
      if ($friend->id == $curUser->id){
        $isFriend = true;
        if ($friend->pivot->facebook_friend == true){
          $isFBFriend = true;
        }
      }
    }
    //return $users;

    $fflag = true;
    return view('leaderboard', compact('users', 'curUser', 'isFriend','isFBFriend', 'fflag'));
    //$port = user
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
