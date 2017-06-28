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
    return view('home');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/fb-login', 'FBLoginController@fBRedirect');
Route::get('/fbcb', 'FBLoginController@fbCallback');

Route::get('/search-stocks', 'CompanySearch@stockSearch');
Route::post('/search-cat', 'CompanySearch@showByCategory');
Route::post('/search-name', 'CompanySearch@searchByName');
Route::get('/dashboard', 'CompanySearch@dashboard');
Route::get('/getmsg/{ticker}','CompanySearch@companyHalfYear');
Route::get('/testdaily', 'CompanySearch@dailyInvestScore');

Route::get('/Buy/{order}', 'PortfolioTransactionController@buyStocks');
Route::get('/sell/{order}', 'PortfolioTransactionController@sellStocks');
Route::get('/Short/{order}', 'PortfolioTransactionController@getShort');
Route::get('/payback-shorts/{ticker}/{price}', 'PortfolioTransactionController@buyShort');

Route::get('/leaderboard', 'LeaderboardController@leaderboard');
Route::get('/leaderboard/following', 'LeaderboardController@friends');
Route::get('/leaderboard/usr-prof/{id}', 'LeaderboardController@usrProf');
Route::get('/leaderboard/following/usr-prof/{id}', 'LeaderboardController@friendProf');
Route::get('/change-fr-status/{id}/{status}','TradingController@friendOrFoe');

Route::get('/autocomplete/{key}','CompanySearch@autocomplete');
Route::get('/my-cart','TradingController@viewCart');
Route::get('/get-price/{ticker}','CompanySearch@get_price');
Route::get('/add-to-cart/{item}','TradingController@addToCart');
Route::get('/del-item/{item}','TradingController@delItem');
