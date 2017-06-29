<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      factory(App\User::class, 50)->create();
      App\User::create(array(
        'name' => 'Bruce Wayne',
        'email' => 'bman@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 20000,
        'invest_score' => 160000.87,
        'shopping_cart' => json_encode(array())
      ));
      $faker = Faker\Factory::create();

      $tickerArray = array('AMZN', 'FB', 'MSFT', 'MMM', 'GOOGL', 'AAL', 'CVX',
      'CINF', 'DE', 'HAL', 'KHC', 'MNST', 'QCOM', 'DIS', 'WFC', 'WMT', 'WFM');
      //$a[mt_rand(0, count($a) - 1)]
      $dt = Carbon::now()->format('Y-m-d');

      for ($i=1;$i<52;$i++){
        $rand_keys = array_rand($tickerArray, 3);
        DB::table('portfolio')->insert([
          ['user_id' => $i, 'stock_ticker' => $tickerArray[$rand_keys[0]],
          'shares' => $faker->numberBetween($min = 1, $max = 200), 'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 15, $max = 1000)],
          ['user_id' => $i, 'stock_ticker' => $tickerArray[$rand_keys[1]],
          'shares' => $faker->numberBetween($min = 1, $max = 200), 'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 15, $max = 1000)],
          ['user_id' => $i, 'stock_ticker' => $tickerArray[$rand_keys[2]],
          'shares' => $faker->numberBetween($min = 1, $max = 200), 'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 15, $max = 1000)]
        ]);
        for($j=10; $j<25; $j++){
          DB::table('scores')->insert(['user_id'=>$i, 'score'=>$faker->randomFloat($nbMaxDecimals = 2, $min=0, $max=1000000), 'date'=>'2017-06-'.$j]);
        }
        $moreRand = array_rand($tickerArray,2);
        DB::table('shorts')->insert([
          ['user_id' => $i, 'stock_ticker' => $tickerArray[$moreRand[0]], 'shares' => $faker->numberBetween($min = 1, $max = 200),
          'initial_price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 15, $max = 1000), 'shorted_at' => $dt],
          ['user_id' => $i, 'stock_ticker' => $tickerArray[$moreRand[1]], 'shares' => $faker->numberBetween($min = 1, $max = 200),
          'initial_price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 15, $max = 1000), 'shorted_at' => $dt]
        ]);
      }

    }
}
