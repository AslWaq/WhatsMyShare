<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Short extends Model
{
    protected $table = 'shorts';

    public function user(){
      return $this->belongsTo ('App\User','user_id');
    }
}
