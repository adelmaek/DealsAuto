<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\generalTransaction;
use App\CashTransaction;
use  App\OperatingExpenses;
use DB;
use DataTables;
use Log;

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
        {
            $cashNoteInput = $request['noteInput'] . " - " . "مصروفات تشغيل";
            CashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'sub', $cashNoteInput, 'custodyCash');
        }
        return redirect()->back();
    }

    public function getDelOperatingExpTrans($trans_id)
    {
        OperatingExpenses::del_transaction($trans_id);
        return redirect()->back();
    }
    public function getQueryOperatingExpenses()
    {
        return view("operatingExpenses/queryTrans");
    }
    public function getQueriedOperatingExpenses($note, $fromDate, $toDate)
    {
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            if(!strcmp($note,"all"))
            {
                $transaction = OperatingExpenses::orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = OperatingExpenses::orderBy('date', 'desc')->where('note', 'like', '%'. $note .'%') ->get();
            }
        
        }
        elseif (!strcmp($toDate,"empty"))
        {            
            if(!strcmp($note,"all"))
            {
                $transaction = OperatingExpenses::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = OperatingExpenses::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->orderBy('date', 'desc')->where('note', 'like', '%'. $note .'%') ->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($note,"all"))
            {
                $transaction = OperatingExpenses::whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = OperatingExpenses::where('note', 'like', '%'. $note .'%')->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }  
        }
        else
        {
            if(!strcmp($note,"all"))
            {
                $transaction = OperatingExpenses::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = OperatingExpenses::where('note', 'like', '%'. $note .'%')-> whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
        }
        $transaction = generalTransaction::separate_add_from_sub($transaction);
        return Datatables::of($transaction)->make(true);
    }
}