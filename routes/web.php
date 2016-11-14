<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'HomeController@show');
Route::get('/juegos', 'HomeController@horarios');
Route::get('/cambiar', 'HomeController@cambiar');
Route::get('/reservar', 'HomeController@reservar');
Route::post('/reservar', 'BookingController@create');
Route::get('/getreserva', 'BookingController@show');
Route::get('/cancelar', 'BookingController@destroy');
Route::get('/update', 'BookingController@update');
Route::get('/bugsnag', 'HomeController@error');
Route::post('/verificar', 'HomeController@fake');
