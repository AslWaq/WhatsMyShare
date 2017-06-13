<?php

use Illuminate\Database\Seeder;
use Crockett\CsvSeeder\CsvSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tickers')->delete();
        DB::table('users')->delete();
        DB::table('portfolio')->delete();
        DB::table('shorts')->delete();

        $this->call(TickersTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
