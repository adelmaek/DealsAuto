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
    Route::post('/homeSubmit',[
        'uses' => 'UserController@postHome',
        'as' => 'homeSubmit',
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
    // Route::get('/cashContent',[
    //     'uses' => 'CashTransactionController@getCashContent',
    //     'as' => 'cashContent',
    //     'middleware' => 'auth'
    // ]);
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

    Route::get('/getCashQueriedTrans/{cashName},{fromDate},{toDate}',[
        'uses' => 'CashTransactionController@getQueriedTransaction',
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
    Route::get('/queiredInvoices/{supplier_name},{fromDate},{toDate}',[
        'uses' => 'BillController@getQueiredInvoices',
        'as' => 'queiredInvoices',
        'middleware' =>'auth'
    ]);
    Route::get('/invoicesTaxes',[
        'uses'=>'BillController@getQueryInvoiceTaxes',
        'as' => 'invoicesTaxes',
        'middleware' => 'auth'
        
    ]);
    //================================================================================================
    // end bills
    //================================================================================================

    //================================================================================================
    // Purchases
    //================================================================================================
    Route::get('/purchases',[
        'uses' => 'PurchaseTransactionController@getPurchasesTransactions',
        'as'=> 'purchases',
        'middleware' => 'auth'
    ]);
    Route::post('/purchases',[
        'uses' => 'PurchaseTransactionController@postPurchasesTransactions',
        'as'=> 'insertPurchasesTransactions',
        'middleware' => 'auth'
    ]);
    Route::get('/delPurchaseTransaction/{trans_id}',[
        'uses' => 'PurchaseTransactionController@getDelPurchaseTransaction',
        'as'=> 'delPurchaseTransaction',
        'middleware' => 'auth'
    ]);
   
    Route::get('queryPurchaseTrans',[
        "uses" => 'PurchaseTransactionController@getQueryPurchaseTransaction',
        'as' => 'queryPurchaseTrans',
        'middleware' => 'auth'
    ]);
    Route::get('/getQueriedPurchaseTrans/{type},{fromDate},{toDate}',[
        'uses' => 'PurchaseTransactionController@getQueriedPurchaseTransactions',
        'as' => 'getQueriedPurchaseTrans',
        'middleware' => 'auth'
    ]);
    //================================================================================================
    // end Purchases
    //================================================================================================

    //================================================================================================
    // MiscellaneousIncome
    //================================================================================================
    Route::get('/MITrans',[
        'uses' => 'MiscellaneousIncomeController@getMITransactions',
        'as'=> 'MITrans',
        'middleware' => 'auth'
    ]);
    Route::post('/MITrans',[
        'uses' => 'MiscellaneousIncomeController@postMITransactions',
        'as'=> 'insertMITrans',
        'middleware' => 'auth'
    ]);
    Route::get('/delMITrans/{MITransaction_id}',[
        'uses' => 'MiscellaneousIncomeController@getDelMITransaction',
        'as'=> 'delMITrans',
        'middleware' => 'auth'
    ]);
   
    //================================================================================================
    // end MiscellaneousIncome
    //================================================================================================

     //================================================================================================
    // Partners
    //================================================================================================
    Route::get('/addPartner',[
        'uses' => 'PartnerController@getAddPartner',
        'as'=> 'addPartner',
        'middleware' => 'auth'
    ]);
    Route::post('/addPartner',[
        'uses' => 'PartnerController@postAddPartner',
        'as'=> 'addPartner',
        'middleware' => 'auth'
    ]);
    Route::get('/delPartner/{partner_id}',[
        'uses' => 'PartnerController@getDelPartner',
        'as'=> 'delPartner',
        'middleware' => 'auth'
    ]);

    Route::get('/addPartnerTrans',[
        'uses' => 'PartnerTransactionController@getAddPartnerTransaction',
        'as'=> 'addPartnerTrans',
        'middleware' => 'auth'
    ]);
    Route::post('/addPartnerTrans',[
        'uses' => 'PartnerTransactionController@postAddPartnerTransaction',
        'as'=> 'addPartnerTrans',
        'middleware' => 'auth'
    ]);

    Route::get('/delPartnerTrans/{trans_id}',[
        'uses' => 'PartnerTransactionController@getDelPartnerTransaction',
        'as'=> 'delPartnerTrans',
        'middleware' => 'auth'
    ]);

    Route::get('/queryPartnerTrans',[
        "uses" => 'PartnerTransactionController@getQueryPartnerTransaction',
        'as' => 'queryPartnerTrans',
        'middleware' => 'auth'
    ]);
    
    Route::get('/getQueriedPartnersTrans/{partner},{fromDate},{toDate}',[
        'uses' => 'PartnerTransactionController@getQueriedPartnerTransactions',
        'as' => 'getQueriedPartnerTrans',
        'middleware' => 'auth'
    ]);
     //================================================================================================
    // end Partners
    //================================================================================================

    //================================================================================================
    // GeneralExpensesTransactions
    //================================================================================================

    Route::get('/generalExpenses',[
        'uses' => 'GeneralExpensesTransactionController@getAddGenExpTrans',
        'as'=> 'generalExpenses',
        'middleware' => 'auth'
    ]);
    Route::post('/generalExpenses',[
        'uses' => 'GeneralExpensesTransactionController@postAddGenExpTrans',
        'as'=> 'generalExpenses',
        'middleware' => 'auth'
    ]);
    Route::get('/delGeneralExpenses/{trans_id}',[
        'uses' => 'GeneralExpensesTransactionController@getDelGenExpTrans',
        'as'=> 'delGeneralExpenses',
        'middleware' => 'auth'
    ]);

    //================================================================================================
    // end GeneralExpensesTransactions
    //================================================================================================

    //================================================================================================
    // taxesTransactions
    //================================================================================================

    Route::get('/TaxesTrans',[
        'uses' => 'TaxesController@getAddTaxesTrans',
        'as'=> 'TaxesTrans',
        'middleware' => 'auth'
    ]);
    Route::post('/TaxesTrans',[
        'uses' => 'TaxesController@postAddTaxesTrans',
        'as'=> 'TaxesTrans',
        'middleware' => 'auth'
    ]);
    Route::get('/delTaxesTransaction/{trans_id}',[
        'uses' => 'TaxesController@getDelTaxesTrans',
        'as'=> 'delTaxesTransaction',
        'middleware' => 'auth'
    ]);
    Route::get('/addedValue',[
        'uses' => 'TaxesController@getAddedValue',
        'as'=> 'addedValue',
        'middleware' => 'auth'
    ]);
    Route::get('/taxAuth',[
        'uses' => 'TaxesController@getTaxAuth',
        'as'=> 'taxAuth',
        'middleware' => 'auth'
    ]);
    //================================================================================================
    // end taxesTransactions
    //================================================================================================
    //================================================================================================
    // ClientsTransactions
    //================================================================================================
    Route::get('/clientsTrans',[
        'uses' => 'ClientTransactionController@getAddClientTrans',
        'as'=> 'clientsTrans',
        'middleware' => 'auth'
    ]);
    Route::post('/clientsTrans',[
        'uses' => 'ClientTransactionController@postAddClientTrans',
        'as'=> 'clientsTrans',
        'middleware' => 'auth'
    ]);
    Route::get('/delClientTransaction/{trans_id}',[
        'uses' => 'ClientTransactionController@getDelClientTrans',
        'as'=> 'delClientTransaction',
        'middleware' => 'auth'
    ]);
    //================================================================================================
    // end ClientsTransactions
    //================================================================================================
    //================================================================================================
    // salesTransactions
    //================================================================================================
    Route::get('/salesTrans',[
        'uses' => 'SalesTransactionController@getAddsalesTrans',
        'as'=> 'salesTrans',
        'middleware' => 'auth'
    ]);
    Route::post('/salesTrans',[
        'uses' => 'SalesTransactionController@postAddSalesTrans',
        'as'=> 'salesTrans',
        'middleware' => 'auth'
    ]);
    Route::get('/delSalesTransaction/{trans_id}',[
        'uses' => 'SalesTransactionController@getDelSalesTrans',
        'as'=> 'delSalesTransaction',
        'middleware' => 'auth'
    ]);
    //================================================================================================
    // end salesTransactions
    //================================================================================================
    //================================================================================================
    // operatingExpenses
    //================================================================================================

    Route::get('/operatingExpenses',[
        'uses' => 'OperatingExpensesController@getAddOperatingExpTrans',
        'as'=> 'operatingExpenses',
        'middleware' => 'auth'
    ]);
    Route::post('/operatingExpenses',[
        'uses' => 'OperatingExpensesController@postAddOperatingExpTrans',
        'as'=> 'operatingExpenses',
        'middleware' => 'auth'
    ]);
    Route::get('/deloperatingExpenses/{trans_id}',[
        'uses' => 'OperatingExpensesController@getDelOperatingExpTrans',
        'as'=> 'delOperatingExpenses',
        'middleware' => 'auth'
    ]);

    //================================================================================================
    // end operatingExpenses
    //================================================================================================
    //================================================================================================
    // Distributions 
    //================================================================================================

    Route::get('/distributions',[
        'uses' => 'DistributionsTransactionController@getAddDistributionsTransaction',
        'as'=> 'distributions',
        'middleware' => 'auth'
    ]);
    Route::post('/distributions',[
        'uses' => 'DistributionsTransactionController@postAddDistributionsTransaction',
        'as'=> 'distributions',
        'middleware' => 'auth'
    ]);
    Route::get('/delDistTrans/{trans_id}',[
        'uses' => 'DistributionsTransactionController@getDelDistributionsTransaction',
        'as'=> 'delDistTrans',
        'middleware' => 'auth'
    ]);

    //================================================================================================
    // end Distributions 
    //================================================================================================
    //================================================================================================
    // Fore Payments 
    //================================================================================================

    Route::get('/forePayment',[
        'uses' => 'ForePaymentController@getAddForePayment',
        'as'=> 'forePayment',
        'middleware' => 'auth'
    ]);
    Route::post('/forePayment',[
        'uses' => 'ForePaymentController@postAddForePayment',
        'as'=> 'forePayment',
        'middleware' => 'auth'
    ]);
    Route::get('/delForePayment/{trans_id}',[
        'uses' => 'ForePaymentController@getDelForePayment',
        'as'=> 'delForePayment',
        'middleware' => 'auth'
    ]);
    //================================================================================================
    // end fore payments 
    //================================================================================================
});


