<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesTransaction;
use App\generalTransaction;
use DB;

class SalesTransactionController extends Controller
{
    public function getAddSalesTrans()
    {
        $salesTransactions = SalesTransaction::orderBy('date','Asc')->get();
        $salesTransactions = generalTransaction::separate_add_from_sub($salesTransactions);
        return view('SalesTransactions/addRemoveTransaction',['salesTransactions'=>$salesTransactions]);
    }
    public function postAddSalesTrans(Request $request)
    {
        SalesTransaction::insertTransaction($request['typeInput'],$request['valueInput'],$request['dateInput'],$request['noteInput']);
        return redirect()->back();
    }
    public function getDelSalesTrans ($SalesTransaction_id)
    {
        SalesTransaction::delTransaction($SalesTransaction_id);
        return redirect()->back();
    }
}
