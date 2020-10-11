<?php

namespace App\Http\Controllers;

use App\GeneralExpensesTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\CashTransaction;
use App\generalTransaction;

class GeneralExpensesTransactionController extends Controller
{
    public function getAddGenExpTrans()
    {
        $transactions = GeneralExpensesTransaction::orderBy('date','Asc')->get();
        $transactions = generalTransaction::separate_add_from_sub($transactions);
        return view('GenExpTransactions/addGenExpTrans',['transactions'=>$transactions]);
    }

    public function postAddGenExpTrans(Request $request)
    {
        GeneralExpensesTransaction::insert_transaction($request['typeInput'], $request['noteInput'], $request['valueInput'],$request['dateInput']);
        if(!strcmp($request['sourceInput'],'custodyCash'))
        {
            $cashNoteInput = $request['noteInput'] . " - " . "مصروفات عامة";
            CashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'sub', $request['noteInput'], 'custodyCash');
        }
        return redirect()->back();
    }

    public function getDelGenExpTrans($trans_id)
    {
        GeneralExpensesTransaction::del_transaction($trans_id);
        return redirect()->back();
    }


}
