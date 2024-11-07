<?php

// Old routes i got from web rentacar-old
//Route::get('/', 'HomeController@index')->name('home');
//    Route::resource('/user', 'UserController');
//    Route::resource('/cars', 'CarController');
//    Route::resource('/registers', 'RegisterController');
//    Route::resource('/clients', 'ClientController');
//    Route::get('/clients/{id}/registers', 'ClientController@clientRegisters')->name('client.registers');
//    Route::get('/registers/create/{client_id?}', 'RegisterController@create')->name('registers.create');
//    Route::get('/registers/download/{id}', 'RegisterController@download')->name('registers.download');

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
//Route::group(['middleware' => 'auth:api'], function () {
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('/me', [AuthController::class, 'me']);

Route::get('/car/notify', [\App\Http\Controllers\CarController::class, 'notify']);

Route::apiResource('clients', \App\Http\Controllers\ClientController::class);
Route::apiResource('cars', \App\Http\Controllers\CarController::class);
Route::apiResource('registers', \App\Http\Controllers\RegisterController::class);

Route::get('/clients/{id}/registers',
    [\App\Http\Controllers\ClientController::class, 'clientRegisters'])->name('client.registers');
//    Route::get('/registers/download/{id}', 'RegisterController@download')->name('registers.download');

//});

