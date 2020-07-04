<?php

namespace App\Http\Controllers;
use App\cashTransaction;
use DB;
use Illuminate\Http\Request;
use Log;
use DataTables;


class CashTransactionController extends Controller
{
    // public function getCashContent()
    // {
    //     $cashContent = cashTransaction::orderBy('date','Asc')->get();
    //     return view('cash/cashContent',['cashContent'=>$cashContent]);
    // }

    public function getAddRemoveCash()
    {
        $cashTransactions = cashTransaction::orderBy('date','Asc')->get();
        return view('cash/addRemoveCash',['cashTransactions'=>$cashTransactions]);
    }

    public function postAddRemoveCash(Request $request)
    {
        cashTransaction::insert_transaction($request['valueInput'],$request['dateInput'],$request['typeInput'], $request['noteInput'],$request['nameInput']);
        return redirect()->back();
    }

    public function getDelCashTransaction($cashTransaction_id)
    {
        cashTransaction::del_transaction($cashTransaction_id);
        return redirect()->back();
    }
    
    public function getQueryCashTransaction()
    {
        $currencies = cashTransaction::all();
        return view('cash/queryCashTransactions',['currencies'=>$currencies]);
    }

    public function getQueriedTransaction( $fromDate, $toDate)
    {
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            Log::debug('in getquered');
            $transaction = cashTransaction::all();
        
        }
        elseif (!strcmp($toDate,"empty"))
        {            
            $transaction = cashTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'DESC')->get();          
        }
        elseif (!strcmp($fromDate,"empty"))
        {

                $transaction = cashTransaction::whereDate('date','<=',$toDate)->orderBy('date', 'DESC')->get();
           
        }
        else
        {
            $transaction = cashTransaction::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'DESC')->get(); 
        }
        foreach($transaction as $trans)
        {
            if(!strcmp('normalCash', $trans->name))
                $trans->name = 'الخزنة';
            else
                $trans->name = 'خزنة العهدة';
        }
        return Datatables::of($transaction)->make(true);
    }
}
