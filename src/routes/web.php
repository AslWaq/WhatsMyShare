<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'CompanySearch@dashboard');
Route::get('/fb-login', 'TransactionController@redrect');
Route::get('/fbcb', 'TransactionController@fbCallback');
Route::get('/search-stocks', 'TransactionController@stockSearch');
Route::post('/search-cat', 'CompanySearch@showByCategory');
Route::get('/buy/{order}', 'PortfolioTransactionController@buyStocks');
Route::get('/autocomplete/{key}','CompanySearch@autocomplete');
Route::get('/getmsg/{ticker}','CompanySearch@companyHalfYear');
Route::get('/my-cart','TradingController@viewCart');
Route::get('/get-price/{ticker}','CompanySearch@get_price');
Route::get('/add-to-cart/{item}','TradingController@addToCart');
Route::get('/testdaily', 'CompanySearch@dailyInvestScore');
Route::get('/del-item/{item}','TradingController@delItem');
