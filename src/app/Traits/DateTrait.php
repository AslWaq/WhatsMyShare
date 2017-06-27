<?php
namespace App\Traits;
use Carbon\Carbon;

trait DateTrait {
  public function getDateString(){
    $dt = Carbon::now();
    //check if saturday or sunday and move back to friday
    if ($dt->dayOfWeek == Carbon::SATURDAY){
      $dt = $dt->subDays(1);
    }elseif ($dt->dayOfWeek == Carbon::SUNDAY){
      $dt = $dt->subDays(2);
    }elseif ($dt->dayOfWeek == Carbon::MONDAY){
      $dt = $dt->subDays(3);
    }else{
      $dt->subDays(1);
    }

    //2017 holiday check
    if($dt->isSameDay(Carbon::createFromDate(2017,05,29))){ //memorial day
      $dt = $dt->subDays(3);
    }elseif ($dt->isSameDay(Carbon::createFromDate(2017,07,04))){ //independence day
      $dt = $dt->subDays(1);
    }
    return $dt->format('Ymd');
  }
}
