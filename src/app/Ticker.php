<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    protected $table = 'tickers';
    public $timestamps = false;
}
