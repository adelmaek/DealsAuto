<?php

namespace App\Http\Controllers;
use App\cashTransaction;
use DB;
use Illuminate\Http\Request;
use Log;
use DataTables;
class CashTransactionController extends Controller
{
    public function getCashContent()
    {
        $cashContent = DB::table('cash_currency')->get();
        return view('cash/cashContent',['cashContent'=>$cashContent]);
    }
    public function getAddRemoveCash()
    {
        $cashTransactions = cashTransaction::all();
        return view('cash/addRemoveCash',['cashTransactions'=>$cashTransactions]);
    }

    public function postAddRemoveCash(Request $request)
    {
        $currencyTotal = DB::table('cash_currency')->where('currency', $request['currencyInput'])->first();

        Log::debug($request->currnecyInput);
        if($currencyTotal)
        {
            
            if(!strcmp($request['typeInput'],"add") )
            {
                Log::debug("add cond");
                DB::table('cash_transactions')->insert([
                    'value' => $request['valueInput'],
                    'date' => $request['dateInput'],
                    'type' => 'ايداع',
                    'currency'=> $request['currencyInput'],
                    'note' => $request['noteInput'],
                    'currentTotal' =>  $currencyTotal-> value + $request['valueInput']
                ]);
                DB::table('cash_currency')->where('currency', $request['currencyInput'])->increment('value',$request['valueInput']);
            }
            else
            {   
                Log::debug("sub cond");
                DB::table('cash_transactions')->insert([
                'value' => $request['valueInput'],
                'date' => $request['dateInput'],
                'type' => 'سحب',
                'currency'=> $request['currencyInput'],
                'note' => $request['noteInput'],
                'currentTotal' =>  $currencyTotal-> value - $request['valueInput']
                 ]);
                DB::table('cash_currency')->where('currency',  $request['currencyInput'])->decrement('value',$request['valueInput']);
            }
        }
        else
        {
            Log::debug("first add");
            DB::table('cash_transactions')->insert([
                'value' => $request['valueInput'],
                'date' => $request['dateInput'],
                'type' => 'ايداع',
                'currency'=> $request['currencyInput'],
                'note' => $request['noteInput'],
                'currentTotal' =>   $request['valueInput']
            ]);
            DB::table('cash_currency')->insert([
                'value' => $request['valueInput'],
                'currency'=> $request['currencyInput']
            ]);

        }
        return redirect()->back();
    }

    public function getDelCashTransaction($cashTransaction_id)
    {
        $transaction = cashTransaction::where('id',$cashTransaction_id)->first();
        if(!strcmp($transaction->type,"ايداع") )
        {
            DB::table('cash_currency')->where('currency', $transaction->currency)->decrement('value',$transaction->value);
        }
        else if(!strcmp($transaction->type,"سحب"))
        {
            DB::table('cash_currency')->where('currency', $transaction->currency)->increment('value',$transaction->value);
        }
        
        $transaction->delete();
        
        return redirect()->back();
    }
    public function getQueryCashTransaction()
    {
        $currencies = cashTransaction::all();
        return view('cash/queryCashTransactions',['currencies'=>$currencies]);
    }

    public function getQueriedTransaction($currency, $fromDate, $toDate)
    {
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            if(!strcmp($currency,"all"))
            {
                $transaction = cashTransaction::all();
            }
            else
            {
                // Log::debug($bank);
                $transaction = cashTransaction::where('currency',$currency)->get();
                // Log::debug($transaction);
            }
        }
        elseif (!strcmp($toDate,"empty"))
        {
            if(!strcmp($currency,"all"))
            {
                $transaction = cashTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'DESC')->get();
            }
            else
            {
                $transaction = cashTransaction::where('currency',$currency)->whereDate('date','>=',$fromDate)->orderBy('date', 'DESC')->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($currency,"all"))
            {
                $transaction = cashTransaction::whereDate('date','<=',$toDate)->orderBy('date', 'DESC')->get();
            }
            else
            {
                $transaction = cashTransaction::where('currency',$currency)->whereDate('date','<=',$toDate)->orderBy('date', 'DESC')->get();
            }
        }
        else
        {
            if(!strcmp($currency,"all"))
            {
                $transaction = cashTransaction::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'DESC')->get();
            }
            else
            {
                $transaction = cashTransaction::where('currency',$currency)->whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'DESC')->get();
            }
        }
        // Log::debug($transaction);
        return Datatables::of($transaction)->make(true);
    }
}
