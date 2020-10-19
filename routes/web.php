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




Auth::routes(['register' => false]);

Route::get('/', 'ChatController@welcome')->name('welcome');
Route::get('/home', 'ChatController@index')->name('home');
Route::post('/save-client', 'HomeController@saveClient')->name('save-client');
