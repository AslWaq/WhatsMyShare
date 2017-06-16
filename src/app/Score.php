<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
  protected $table = 'scores';
  public $timestamps = false;
  protected $fillable = [
  'user_id', 'score', 'date'
  ];

  public function user(){
    return $this->belongsTo('App\User','user_id');
  }
}
