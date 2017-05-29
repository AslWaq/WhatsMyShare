<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    protected $table = 'tickers';
    protected $primaryKey = 'ticker';
    public $incrementing = false;
    public $timestamps = false;
}
