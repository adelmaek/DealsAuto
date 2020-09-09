<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\generalTransaction;
use App\cashTransaction;
use  App\OperatingExpenses;

class OperatingExpensesController extends Controller
{
    public function getAddOperatingExpTrans()
    {
        $transactions = OperatingExpenses::orderBy('date','Asc')->get();
        $transactions = generalTransaction::separate_add_from_sub($transactions);
        return view('operatingExpenses/addRemoveTransaction',['transactions'=>$transactions]);
    }

    public function postAddOperatingExpTrans(Request $request)
    {
        OperatingExpenses::insert_transaction($request['typeInput'], $request['noteInput'], $request['valueInput'],$request['dateInput']);
        if(!strcmp($request['sourceInput'],'custodyCash'))
            cashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'sub', $request['noteInput'], 'custodyCash');
        return redirect()->back();
    }

    public function getDelOperatingExpTrans($trans_id)
    {
        OperatingExpenses::del_transaction($trans_id);
        return redirect()->back();
    }
}