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
Route::resource('foods', 'FoodController');
Route::resource('tables', 'TableController');

Route::get('sales/getfoods', 'SaleController@getfoods');
Route::post('sales/paynow', 'SaleController@paynow');
Route::resource('sales', 'SaleController');
Route::post('pay', 'SaleController@pay');
Route::resource('reports', 'ReportController');
Route::resource('expends', 'ExpendController');
