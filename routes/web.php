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

// Route::get('/', function () {
//     return view('welcome');
// });

//Route::get('/dashboard', 'DashboardController@dashboard');
// Route::get('/client', function () {
//     return view('dashboard.dashboard');
// });

Route::get('/{path?}', function(){
    return view( 'index' );
} )->where('path', '.*');
// Route::get('/clear-cache', function() {
//     $exitCode = Artisan::call('config:clear');
//     $exitCode = Artisan::call('cache:clear');
//     $exitCode = Artisan::call('config:cache');
//     return 'DONE'; //Return anything
// });

//Route::resource('/users', 'UserController');
