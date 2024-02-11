<?php

use Illuminate\Support\Facades\Route;

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

 //perdoruesit
Auth::routes(['register' => false]);

//user needs to be logged in routes
Route::group(['middleware' => 'auth'], function(){
    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('/user', 'UserController');
    Route::resource('/cars', 'CarController');
    Route::resource('/registers', 'RegisterController');
    Route::resource('/clients', 'ClientController');
    Route::get('/clients/{id}/registers', 'ClientController@clientRegisters')->name('client.registers');
    Route::get('/registers/create/{client_id?}', 'RegisterController@create')->name('registers.create');
    Route::get('/registers/download/{id}', 'RegisterController@download')->name('registers.download');
    Route::get('/logout', 'Auth\LoginController@logout');

    Route::get('/test', 'RegisterController@test');

});
