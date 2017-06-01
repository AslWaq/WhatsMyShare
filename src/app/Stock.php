<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'portfolio';

    //user that this row belongs to
    public function user(){
      return $this->belongsTo ('App\User', 'user_id');
    }
}
