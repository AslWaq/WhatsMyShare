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
        
        $this->call(TickersTableSeeder::class);
    }
}
