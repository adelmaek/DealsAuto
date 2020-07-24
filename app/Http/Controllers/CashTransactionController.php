<?php

namespace App\Http\Controllers;
use App\cashTransaction;
use DB;
use Illuminate\Http\Request;
use Log;
use DataTables;
use App\generalTransaction;


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
        $cashTransactions = generalTransaction::separate_add_from_sub($cashTransactions);
        return view('cash/addRemoveCash',['cashTransactions'=>$cashTransactions]);
    }

    public function postAddRemoveCash(Request $request)
    {
        if(!strcmp($request['typeInput'],"fromNormalCashToCustodyCash"))
        {
            cashTransaction::insert_transaction($request['valueInput'],$request['dateInput'],'sub', $request['noteInput'],"normalCash");
            cashTransaction::insert_transaction($request['valueInput'],$request['dateInput'],"add", $request['noteInput'],"custodyCash");
        }
        else
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
        $transaction = generalTransaction::separate_add_from_sub($transaction);
        return Datatables::of($transaction)->make(true);
    }
}
