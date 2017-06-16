<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;

class User extends Authenticatable
{
    use Notifiable;
    use SyncableGraphNodeTrait;
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected static $graph_node_field_aliases = [
      'id' => 'facebook_user_id'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','facebook_user_id', 'access_token', 'cash', 'invest_score', 'shopping_cart'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'access_token'
    ];

    public function scores(){
      return $this->hasMany('App\Score', 'user_id');
    }
    //retrieve stocks from portfolio for users
    public function stocks(){
      return $this->hasMany('App\Stock','user_id');
    }

    //retrieve shorted stocks for users
    public function shorts(){
      return $this->hasMany('App\Short', 'user_id');
    }

    //retrieve friends/ppl you're following
    public function friends(){
      return $this->belongsToMany('App\User','friends_users','user_id','friend_id')->withPivot('facebook_friend');
    }

    public function addFriend($id, bool $FB){
      $this->friends()->attach($id, ['facebook_friend' => $FB]);
    }

    public function removeFriend($id){
      $this->friends()->detach($id);
    }
}
