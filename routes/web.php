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
Route::group(['middleware'=>['web']],function(){
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    
    Route::get('/home',[
        'uses' => 'UserController@getHome',
        'as' => 'home',
        'middleware' => 'auth'
    ]);
    
    Route::post('/signin',[
        'uses' => 'UserController@postSignin',
        'as' => 'signin'
    ]);
    Route::post('/logout',[
        'uses' => '\App\Http\Controllers\Auth\LoginController@logout',
        'as' => 'logout'
    ]);

    Route::get('/addBank', [
        'uses' => 'BankController@getAddBank',
        'as' => 'addBank',
        'middleware' => 'auth'
    ]);

    Route::post('/addBank',[
        'uses' => 'BankController@postInsertBank',
        'as' => 'insertBank',
        'middleware' => 'auth'
    ]);

});

