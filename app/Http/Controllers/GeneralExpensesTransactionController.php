<?php

namespace App\Http\Controllers;

use App\GeneralExpensesTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\CashTransaction;
use App\generalTransaction;
use DataTables;
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
            CashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'sub', $cashNoteInput, 'custodyCash');
        }
        else if(!strcmp($request['sourceInput'],'normalCash'))
        {
            $cashNoteInput = $request['noteInput'] . " - " . "مصروفات عامة";
            CashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'sub', $cashNoteInput, 'normalCash');
        }
        return redirect()->back();
    }

    public function getDelGenExpTrans($trans_id)
    {
        GeneralExpensesTransaction::del_transaction($trans_id);
        return redirect()->back();
    }

    public function getQueryGeneralExpenses()
    {
        return view("GenExpTransactions.queryTrans");
    }
    public function getQueriedGeneralExpenses($note, $fromDate, $toDate)
    {
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            if(!strcmp($note,"all"))
            {
                $transaction = GeneralExpensesTransaction::orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = GeneralExpensesTransaction::orderBy('date', 'desc')->where('note', 'like', '%'. $note .'%') ->get();
            }
        
        }
        elseif (!strcmp($toDate,"empty"))
        {            
            if(!strcmp($note,"all"))
            {
                $transaction = GeneralExpensesTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = GeneralExpensesTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->orderBy('date', 'desc')->where('note', 'like', '%'. $note .'%') ->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($note,"all"))
            {
                $transaction = GeneralExpensesTransaction::whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = GeneralExpensesTransaction::where('note', 'like', '%'. $note .'%')->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }  
        }
        else
        {
            if(!strcmp($note,"all"))
            {
                $transaction = GeneralExpensesTransaction::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = GeneralExpensesTransaction::where('note', 'like', '%'. $note .'%')-> whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
        }
        $transaction = generalTransaction::separate_add_from_sub($transaction);
        return Datatables::of($transaction)->make(true);
    }

}
