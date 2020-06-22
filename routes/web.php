<?php

use Illuminate\Support\Facades\Route;
use App\Bank;
use App\Supplier;
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
    $suppliers = Supplier::all();
    $view->with(['banks'=>$banks,'suppliers'=>$suppliers]);
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
    // end cashTransactions
    //================================================================================================

    //================================================================================================
    // Suppliers
    //================================================================================================
        Route::get('/suppliers',[
            'uses' => 'SupplierController@getSuppliers',
            'as'=> 'suppliers',
            'middleware' => 'auth'
        ]);
        Route::post('/suppliers',[
            'uses' => 'SupplierController@postSuppliers',
            'as'=> 'insertSupplier',
            'middleware' => 'auth'
        ]);
        Route::get('/delSupplier/{supplier_id}',[
            'uses' => 'SupplierController@getDelSupplier',
            'as'=> 'delSupplier',
            'middleware' => 'auth'
        ]);
       Route::get('addRemoveSupplierTrans',[
           "uses" => 'SupplierTransactionController@getInsertTransaction',
           'as' => 'addRemoveSupplierTrans',
           'middleware' => 'auth'
       ]);
       Route::post('addRemoveSupplierTrans',[
            "uses" => 'SupplierTransactionController@postInsertTransaction',
            'as' => 'insertSupplierTrans',
            'middleware' => 'auth'
       ]);
       Route::get('delSupplierTrans/{transaction_id}',[
        "uses" => 'SupplierTransactionController@getDelTransaction',
        'as' => 'delSupplierTrans',
        'middleware' => 'auth'
        ]);
        Route::get('querySupplierTrans',[
            "uses" => 'SupplierTransactionController@getQueryTransaction',
            'as' => 'querySupplierTrans',
            'middleware' => 'auth'
        ]);
        Route::get('/getQueriedSupplierTrans/{supplier},{fromDate},{toDate}',[
            'uses' => 'SupplierTransactionController@getQueriedTransactions',
            'as' => 'getQueriedSupplierTrans',
            'middleware' => 'auth'
        ]);
    //================================================================================================
    // end Suppliers
    //================================================================================================
    

 
    //================================================================================================
    // bills
    //================================================================================================
      Route::get('/newinvoice',[
          'uses' => 'BillController@getAddNewInvoice',
          'as' => 'addNewInvoice',
          'middleware' => 'auth'
      ]);

      Route::any('/addNewInvoice',[
          'uses' => 'BillController@postAddNewInvoice',
          'as' => 'addNewInvoice',
          'middleware' => 'auth'
      ]);
      Route::get('/showInvoices',[
        'uses' => 'BillController@getShowInvoices',
        'as' => 'showInvoices',
        'middleware' => 'auth'
        ]);
      Route::get('/delInvoice/{bill_number}',[
          'uses'=>'BillController@getDelInvoice',
          'as' => 'delInvoice',
          'middleware' => 'auth'
      ]);
        
      Route::get('/showInvoice,{bill_number}',[
        'uses'=>'BillController@getShowInvoice',
        'as' => 'showInvoice',
        'middleware' => 'auth'
    ]);
    Route::get('/queryInvoices',[
        'uses'=>'BillController@getQueryInvoice',
        'as' => 'queryInvoices',
        'middleware' => 'auth'
    ]);
    //================================================================================================
    // end bills
    //================================================================================================
});


