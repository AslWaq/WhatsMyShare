<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Short extends Model
{
    protected $table = 'shorts';
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'stock_ticker', 'shares','initial_price', 'shorted_at'
    ];

    public function user(){
      return $this->belongsTo('App\User','user_id');
    }
}
