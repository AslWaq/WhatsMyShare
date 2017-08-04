<?php

use Illuminate\Database\Seeder;
use Crockett\CsvSeeder\CsvSeeder;

class TickersTableSeeder extends CsvSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('tickers')->delete();
      
      $this->mapping = [
        0 => 'ticker',
        1 => 'name',
        2 => 'category',
        3 => 'link'
      ];

      $this->seedFromCSV(base_path('database/sp500commas.csv'), 'tickers');
    }
}
