<?php

namespace App\Http\Controllers;

use App\GeneralExpensesTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\cashTransaction;

class GeneralExpensesTransactionController extends Controller
{
    public function getAddGenExpTrans()
    {
        $transactions = GeneralExpensesTransaction::orderBy('date','Asc')->get();
        return view('GenExpTransactions/addGenExpTrans',['transactions'=>$transactions]);
    }

    public function postAddGenExpTrans(Request $request)
    {
        GeneralExpensesTransaction::insert_transaction($request['typeInput'], $request['noteInput'], $request['valueInput'],$request['dateInput']);
        if(!strcmp($request['sourceInput'],'custodyCash'))
            cashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'sub', $request['noteInput'], 'custodyCash');
        return redirect()->back();
    }

    public function getDelGenExpTrans($trans_id)
    {
        GeneralExpensesTransaction::del_transaction($trans_id);
        return redirect()->back();
    }


}
