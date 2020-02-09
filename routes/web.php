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

Route::post('/products', 'ProductsController@store');
Route::patch('/products/{product}', 'ProductsController@update');
Route::delete('/products/{product}', 'ProductsController@destroy');


Route::post('/company', 'CompaniesController@store');