<?php

namespace App\Http\Controllers;
use App\CashTransaction;
use DB;
use Illuminate\Http\Request;
use Log;
use DataTables;
use App\generalTransaction;


class CashTransactionController extends Controller
{
    // public function getCashContent()
    // {
    //     $cashContent = CashTransaction::orderBy('date','Asc')->get();
    //     return view('cash/cashContent',['cashContent'=>$cashContent]);
    // }

    public function getAddRemoveCash()
    {
        $CashTransactions = CashTransaction::orderBy('date','Asc')->get();
        $CashTransactions = generalTransaction::separate_add_from_sub($CashTransactions);
        return view('cash/addRemoveCash',['CashTransactions'=>$CashTransactions]);
    }

    public function postAddRemoveCash(Request $request)
    {
        if(!strcmp($request['typeInput'],"fromNormalCashToCustodyCash"))
        {
            CashTransaction::insert_transaction($request['valueInput'],$request['dateInput'],'sub', $request['noteInput'],"normalCash");
            CashTransaction::insert_transaction($request['valueInput'],$request['dateInput'],"add", $request['noteInput'],"custodyCash");
        }
        else
            CashTransaction::insert_transaction($request['valueInput'],$request['dateInput'],$request['typeInput'], $request['noteInput'],$request['nameInput']);
        return redirect()->back();
    }

    public function getDelCashTransaction($CashTransaction_id)
    {
        CashTransaction::del_transaction($CashTransaction_id);
        return redirect()->back();
    }
    
    public function getQueryCashTransaction()
    {
        $currencies = CashTransaction::all();
        return view('cash/queryCashTransactions',['currencies'=>$currencies]);
    }

    public function getQueriedTransaction($cashName, $fromDate, $toDate)
    {
        Log::debug($cashName);
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            if(!strcmp($cashName,"all"))
            {
                $transaction = CashTransaction::orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = CashTransaction::where('name',$cashName)->get();
            }
        
        }
        elseif (!strcmp($toDate,"empty"))
        {            
            if(!strcmp($cashName,"all"))
            {
                $transaction = CashTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = CashTransaction::where('name',$cashName)->whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($cashName,"all"))
            {
                $transaction = CashTransaction::whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = CashTransaction::where('name',$cashName)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }  
        }
        else
        {
            if(!strcmp($cashName,"all"))
            {
                $transaction = CashTransaction::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = CashTransaction::where('name',$cashName)->whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
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
