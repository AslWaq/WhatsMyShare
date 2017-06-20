<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\User;
use Carbon\Carbon;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function () {
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


          $stocks = DB::table('portfolio')->distinct()->select('stock_ticker')->get();
          $tickstring = '';
          foreach ($stocks as $tick){
            $tickstring .= $tick->stock_ticker . ',';
          }
          $date = $dt->format('Ymd');
          $tickstring = substr($tickstring,0,-1);

          $url = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='.$date.
          '&qopts.columns=ticker,close&ticker='.$tickstring.'&api_key=JxDXY6jBDscX9-pYTiov';

          $client = new \GuzzleHttp\Client();
          $res = $client->get(
              $url,
              ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
          );
          $contents = $res->getBody();
          $ar = json_decode($contents, true);
          $ar2 = array_values(array_values($ar)[0]);
          $data = $ar2[0];
          $nowDate = Carbon::now();
          $dte = $nowDate->format('Y-m-d');

          $users = User::all();
          foreach ($users as $user){
            $total = 0;
            if (!$user->stocks()->get()->isEmpty()){
              for ($x=0; $x<count($data); $x++){
                $stock = $user->stocks()->where('stock_ticker', $data[$x][0])->first();
                if (!$stock == null){
                  $total += ($stock->shares * $data[$x][1]);
                }
              }
              $user->invest_score = ($user->cash) + $total;
              $user->save();
            }else{
              $user->invest_score = $user->cash;
              $user->save();
            }
          }

          $shortTickers = DB::table('shorts')->distinct()->select('stock_ticker')->get();
          $tickstring = '';
          if (!empty($shortTickers)){
            foreach ($shortTickers as $tick){
              $tickstring .= $tick->stock_ticker . ',';
            }
            $shortstring = substr($tickstring,0,-1);
            $urlshort = 'https://www.quandl.com/api/v3/datatables/WIKI/PRICES.json?date='.$date.
            '&qopts.columns=ticker,close&ticker='.$shortstring.'&api_key=JxDXY6jBDscX9-pYTiov';

            $shortRes = $client->get(
                $urlshort,
                ['auth' =>  ['api_key', 'JxDXY6jBDscX9-pYTiov', 'digest']]
            );
            $contents = $shortRes->getBody();
            $ar = json_decode($contents, true);
            $ar2 = array_values(array_values($ar)[0]);
            $shortData = $ar2[0];
            foreach ($users as $user){
              if (!$user->shorts->isEmpty()){
                for ($y=0; $y<count($shortData); $y++){
                  $short = $user->shorts()->where('stock_ticker', $shortData[$y][0])->first();
                  if (!empty($short)){
                    $shortDate = new Carbon($short->date);
                    if ($nowDate->diffInDays($shortDate) > 90){
                      $gainOrLoss = (($short->shares) * $shortData[$y][1]) - (($short->shares) * ($short->initial_price));
                      $user->cash -= $gainOrLoss;
                      $user->invest_score -= $gainOrLoss;
                      $short->delete();
                    }
                  }
                }
              }
              $user->save();
            }
          }
          foreach ($users as $user){
            DB::table('scores')->insert(['user_id'=>$user->id, 'score'=>$user->invest_score, 'date'=>$dte]);
          }


        })->weekdays()->at('00:30');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
