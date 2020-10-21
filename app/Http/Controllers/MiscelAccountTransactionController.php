<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MiscelAccount;
use App\MiscelAccountTransaction;
use App\Bank;
use DataTables;

class MiscelAccountTransactionController extends Controller
{
    public function getaddTrans()
    {
        $accounts = MiscelAccount::all();
        $transactions = MiscelAccountTransaction::orderBy('date','Asc')->get();
        $transactions = MiscelAccountTransaction::separate_add_sub_cols($transactions);
        $banks = Bank::all();
        
        return view('MiscellaneousAccounts/addTransaction',['accounts'=>$accounts,'transactions'=>$transactions,'banks'=>$banks]);
    }

    public function postaddTrans(Request $request)
    {
        MiscelAccountTransaction::insert_transaction($request['accountNameInput'],$request['dateInput'], $request['typeInput'], $request['valueInput'],$request['noteInput'],$request['sourceInput']);
        return redirect()->back();
    }

    public function getDelTrans($trans_id)
    {
        MiscelAccountTransaction::del_transaction($trans_id);
        return redirect()->back();
    }

    public function getQueryTrans()
    {
        $accounts = MiscelAccount::all();
        return view('MiscellaneousAccounts/queryTransactions',['accounts'=>$accounts]);
    }

    public function getQueriedTrans($account, $fromDate, $toDate)
    {
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            if(!strcmp($account,"all"))
            {
                $transaction = MiscelAccountTransaction::orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = MiscelAccountTransaction::where('account_id',$account)->get();
            }
                
        }
        elseif (!strcmp($toDate,"empty"))
        {
         
            if(!strcmp($account,"all"))
            {
                $transaction = MiscelAccountTransaction::whereDate('date','<=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = MiscelAccountTransaction::where('account_id',$account)->whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($account,"all"))
            {
                $transaction = MiscelAccountTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = MiscelAccountTransaction::where('account_id',$account)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            
        }
        else
        {
            if(!strcmp($account,"all"))
            {
                $transaction = MiscelAccountTransaction::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = MiscelAccountTransaction::where('account_id',$account)->whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
        }
        // Log::debug($transaction);
        // Log::debug($transaction);
        $transaction = MiscelAccountTransaction::separate_add_sub_cols($transaction);
        return Datatables::of($transaction)->make(true);
    }

}
