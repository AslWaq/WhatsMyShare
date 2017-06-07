<?php

use Illuminate\Database\Seeder;

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
        'shopping_cart' => json_encode(array())
      ));
      $user2 = App\User::create(array(
        'name' => 'Diana Prince',
        'email' => 'wonderwoman@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 108000.49,
        'shopping_cart' => json_encode(array())
      ));
      $user3 = App\User::create(array(
        'name' => 'Patrick Bateman',
        'email' => 'ampsycho@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 2340.94,
        'shopping_cart' => json_encode(array())
      ));
      $user4 = App\User::create(array(
        'name' => 'Jane Macron',
        'email' => 'jmac@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 46879.53,
        'shopping_cart' => json_encode(array())
      ));
      $user5 = App\User::create(array(
        'name' => 'Matt Damon',
        'email' => 'bourne@gmail.com',
        'password' => bcrypt('test12'),
        'cash' => 185.86,
        'shopping_cart' => json_encode(array())
      ));

      DB::table('portfolio')->insert([
        ['user_id' => $user1 -> id, 'stock_ticker' => 'FB', 'shares' => 100, 'price' => 151.46],
        ['user_id' => $user1 -> id, 'stock_ticker' => 'MSFT', 'shares' => 100, 'price' => 69.84],
        ['user_id' => $user1 -> id, 'stock_ticker' => 'AMZN', 'shares' => 126, 'price' => 994.62],
        ['user_id' => $user1 -> id, 'stock_ticker' => 'EBAY', 'shares' => 10, 'price' => 34.30],
        ['user_id' => $user1 -> id, 'stock_ticker' => 'EA', 'shares' => 143, 'price' => 113.33],
        ['user_id' => $user5 -> id, 'stock_ticker' => 'FB', 'shares' => 86, 'price' => 129.42]
      ]);

    }
}
