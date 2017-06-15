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
      $user1 = App\User::create(array(
        'name' => 'Bruce Wayne',
        'email' => 'batman@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 20000,
        'invest_score' => 160000.87,
        'shopping_cart' => json_encode(array())
      ));
      $user2 = App\User::create(array(
        'name' => 'Diana Prince',
        'email' => 'wonderwoman@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 108000.49,
        'invest_score' => 108000.49,
        'shopping_cart' => json_encode(array())
      ));
      $user3 = App\User::create(array(
        'name' => 'Patrick Bateman',
        'email' => 'ampsycho@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 2340.94,
        'invest_score' => 33243.67,
        'shopping_cart' => json_encode(array())
      ));
      $user4 = App\User::create(array(
        'name' => 'Jane Macron',
        'email' => 'jmac@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 46879.53,
        'invest_score' => 49370.20,
        'shopping_cart' => json_encode(array())
      ));
      $user5 = App\User::create(array(
        'name' => 'Matt Damon',
        'email' => 'bourne@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 185.86,
        'invest_score' => 2234.45,
        'shopping_cart' => json_encode(array())
      ));

      $user6 = App\User::create(array(
        'name' => 'George Clooney',
        'email' => 'cloondawg@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 40205.20,
        'invest_score' => 180307.48,
        'shopping_cart' => json_encode(array())
      ));

      $user7 = App\User::create(array(
        'name' => 'Halle Berry',
        'email' => 'halle@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 58265.29,
        'invest_score' => 149125.64,
        'shopping_cart' => json_encode(array())
      ));

      $user8 = App\User::create(array(
        'name' => 'Rihanna Fenty',
        'email' => 'riri@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 625.41,
        'invest_score' => 8048.90,
        'shopping_cart' => json_encode(array())
      ));

      $user1->addFriend($user2->id);
      $user1->addFriend($user3->id);
      $user1->addFriend($user6->id);
      $user1->save();

      $dt = Carbon::now()->format('Y-m-d');

      DB::table('portfolio')->insert([
        ['user_id' => $user1 -> id, 'stock_ticker' => 'FB', 'shares' => 100, 'price' => 151.46],
        ['user_id' => $user1 -> id, 'stock_ticker' => 'MSFT', 'shares' => 100, 'price' => 69.84],
        ['user_id' => $user1 -> id, 'stock_ticker' => 'AMZN', 'shares' => 126, 'price' => 994.62],
        ['user_id' => $user4 -> id, 'stock_ticker' => 'EBAY', 'shares' => 10, 'price' => 34.30],
        ['user_id' => $user4 -> id, 'stock_ticker' => 'EA', 'shares' => 143, 'price' => 113.33],
        ['user_id' => $user5 -> id, 'stock_ticker' => 'FB', 'shares' => 86, 'price' => 129.42],
        ['user_id' => $user3 -> id, 'stock_ticker' => 'BAC', 'shares' => 23, 'price' => 114.35],
        ['user_id' => $user3 -> id, 'stock_ticker' => 'BDX', 'shares' => 14, 'price' => 56.84],
        ['user_id' => $user2 -> id, 'stock_ticker' => 'BBBY', 'shares' => 28, 'price' => 109.87],
        ['user_id' => $user2 -> id, 'stock_ticker' => 'FB', 'shares' => 40, 'price' => 158.46],
        ['user_id' => $user5 -> id, 'stock_ticker' => 'CCL', 'shares' => 20, 'price' => 84.56],
        ['user_id' => $user5 -> id, 'stock_ticker' => 'FB', 'shares' => 86, 'price' => 129.42],
        ['user_id' => $user5 -> id, 'stock_ticker' => 'FB', 'shares' => 86, 'price' => 129.42],
        ['user_id' => $user5 -> id, 'stock_ticker' => 'FB', 'shares' => 86, 'price' => 129.42],
        ['user_id' => $user5 -> id, 'stock_ticker' => 'FB', 'shares' => 86, 'price' => 129.42],
        ['user_id' => $user5 -> id, 'stock_ticker' => 'FB', 'shares' => 86, 'price' => 129.42]
      ]);

      DB::table('shorts')->insert([
        ['user_id' => $user1 -> id, 'stock_ticker' => 'AMZN', 'shares' => 40, 'initial_price' => 563.45, 'shorted_at' => $dt],
        ['user_id' => $user1 -> id, 'stock_ticker' => 'BAC', 'shares' => 48, 'initial_price' => 108.45, 'shorted_at' => $dt]
      ]);

    }
}
