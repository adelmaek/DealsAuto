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
        $allCashTrans =cashTransaction::all();
        $prevTransaction = cashTransaction::whereDate('date','<=',$request['dateInput'])->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = cashTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = cashTransaction::whereDate('date','>',$request['dateInput'])->orderBy('date','Asc')->get();


        if(!empty($prevTransaction))
        {
            if(!strcmp($request['typeInput'],"add"))
                $currentBalanceInput = $prevTransaction->currentTotal +  $request['valueInput'];
            else
                $currentBalanceInput = $prevTransaction->currentTotal -  $request['valueInput'];
        }
        else
        {
 
            $currentBalanceInput =  $request['valueInput'];
        }
        $accumulatedBalance = $currentBalanceInput;
        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            cashTransaction::where('id', $trans->id)-> update(['currentTotal'=>$accumulatedBalance]);
        }


        if(!strcmp($request['typeInput'],"add") )
        {
            Log::debug("add cond");
            DB::table('cash_transactions')->insert([
                'value' => $request['valueInput'],
                'date' => $request['dateInput'],
                'type' => 'ايداع',
                'note' => $request['noteInput'],
                'currentTotal' =>  $currentBalanceInput
            ]);
        }
        else
        {   
            Log::debug("sub cond");
            DB::table('cash_transactions')->insert([
            'value' => $request['valueInput'],
            'date' => $request['dateInput'],
            'type' => 'سحب',
            'note' => $request['noteInput'],
            'currentTotal' =>  $currentBalanceInput
                ]);
        }
        
        return redirect()->back();
    }

    public function getDelCashTransaction($cashTransaction_id)
    {
        $transaction = cashTransaction::where('id',$cashTransaction_id)->first();
        
        $transaction->delete();

        $prevTransaction = cashTransaction::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = cashTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = cashTransaction::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentTotal;
        else
            $currentBalance =  0;
        foreach($followingTransactions as  $trans)
        {
            if(!strcmp($trans->type,"ايداع"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            cashTransaction::where('id', $trans->id)-> update(['currentTotal'=>$currentBalance]);
        }

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
        // Log::debug($transaction);
        return Datatables::of($transaction)->make(true);
    }
}
