<?php

use Illuminate\Support\Facades\Route;
use App\Bank;
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

view()->composer(['layouts/main'], function ($view) {
    $banks = Bank::all();
    $view->with('banks',$banks);
});
Route::group(['middleware'=>['web']],function(){



    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/temp', function () {
        return view('temp');
    })->name('temp');
    
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
    //================================================================================================
    // bank accounts
    //================================================================================================
    Route::get('/showBank,{accountNumber}', [
        'uses' => 'BankController@getShowBank',
        'as' => 'showBank',
        'middleware' => 'auth'
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
    Route::get('/addTransaction', [
        'uses' => 'BankTransactionController@getCreateTransaction',
        'as' => 'addTransaction',
        'middleware' => 'auth'
    ]);
    Route::post('/addTransaction', [
        'uses' => 'BankTransactionController@postCreateTransaction',
        'as' => 'insertTransaction',
        'middleware' => 'auth'
    ]);
    Route::get('/delTransaction/{transaction_id},{accNumber}', [
        'uses' => 'BankTransactionController@getDelTransaction',
        'as' => 'delTransaction',
        'middleware' => 'auth'
    ]);
    //================================================================================================
    // end of bank accounts
    //================================================================================================
    //================================================================================================
    // Query bankTransactions 
    //================================================================================================
    Route::get('/queryTrans',[
        'uses' => 'BankTransactionController@getQueryTransaction',
        'as' => 'queryTransaction',
        'middleware' => 'auth'
    ]);
    
    
    Route::get('/getQueriedTrans/{bank},{fromDate},{toDate}',[
        'uses' => 'BankTransactionController@getQueriedTransaction',
        'as' => 'queriedTransaction',
        'middleware' => 'auth'
    ]);
    //================================================================================================
    // END Query bankTransactions 
    //================================================================================================
    
    
    //================================================================================================
    // cashTransactions 
    //================================================================================================
    Route::get('/cashContent',[
        'uses' => 'CashTransactionController@getCashContent',
        'as' => 'cashContent',
        'middleware' => 'auth'
    ]);
    Route::get('/addRemoveCash',[
        'uses' => 'CashTransactionController@getAddRemoveCash',
        'as' => 'addRemoveCash',
        'middleware' => 'auth'
    ]);
    Route::post('/addRemoveCash',[
        'uses' => 'CashTransactionController@postAddRemoveCash',
        'as' => 'addRemoveCash',
        'middleware' => 'auth'
    ]);
    Route::get('/delCashTransaction/{cashTransaction_id}', [
        'uses' => 'CashTransactionController@getDelCashTransaction',
        'as' => 'delCashTransaction',
        'middleware' => 'auth'
    ]);

    Route::get('/queryCashTrans',[
        'uses' => 'CashTransactionController@getQueryCashTransaction',
        'as' => 'queryCashTransaction',
        'middleware' => 'auth'
    ]);

    Route::get('/getCashQueriedTrans/{currency},{fromDate},{toDate}',[
        'uses' => 'cashTransactionController@getQueriedTransaction',
        'as' => 'queriedTransaction',
        'middleware' => 'auth'
    ]);
    //================================================================================================
    // Query cashTransactions 
    //================================================================================================
});


