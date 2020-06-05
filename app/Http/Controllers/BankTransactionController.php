<?php

namespace App\Http\Controllers;
use App\Bank;
use App\BankTransaction;
use Illuminate\Http\Request;
use DB;
use Log;
use DataTables;
class BankTransactionController extends Controller
{
    public function getCreateTransaction()
    {
        $banks = Bank::all();
        $bankTransactions = BankTransaction::orderBy('date', 'DESC')->get();
        return view('transactions/addTransaction',['banks'=>$banks,'transactions'=>$bankTransactions]);
    }
    public function postCreateTransaction (Request $request)
    {
        $bank = DB::table('banks')->where('accountNumber', $request-> accountNumberInput)->first();
        if(!strcmp($request['typeInput'],"add") )
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $request['accountNumberInput'],
                'date' => $request['dateInput'],
                'type' => "ايداع",
                'value'=> $request['valueInput'],
                'note' => $request['noteInput'],
                'bank_id'=> $bank->id
            ]);
            DB::table('banks')->where('id', $bank-> id)->increment('currentBalance',$request['valueInput']);
        }
        else if(!strcmp($request['typeInput'],"sub"))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $request['accountNumberInput'],
                'date' => $request['dateInput'],
                'type' => "سحب",
                'value'=> $request['valueInput'],
                'note' => $request['noteInput'],
                'bank_id'=> $bank->id
            ]);
            DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$request['valueInput']);
        }
        return redirect()->back();
    }
    public function getDelTransaction ($transaction_id, $accNumber)
    {
        $transaction = BankTransaction::where('id',$transaction_id,)->first();
        // $bank = DB::table('banks')->where('accountNumber', $request-> accountNumberInput)->first();
        if(!strcmp($transaction->type,"add") )
        {
            DB::table('banks')->where('accountNumber', $accNumber)->decrement('currentBalance',$transaction->value);
        }
        else if(!strcmp($transaction->type,"sub"))
        {
            DB::table('banks')->where('accountNumber', $accNumber)->increment('currentBalance',$transaction->value);
        }
        
        $transaction->delete();
        
        return redirect()->back();
    }
    public function getQueryTransaction()
    {
        $banks = Bank::all();
        return view('transactions/queryTransaction',['banks'=>$banks]);
    }
    public function getQueriedTransaction($bank, $fromDate, $toDate)
    {
        // $transaction = BankTransaction::all();
        // Log::debug("here");
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            if(!strcmp($bank,"all"))
            {
                $transaction = BankTransaction::orderBy('date', 'DESC')->get();
            }
            else
            {
                // Log::debug($bank);
                $transaction = BankTransaction::where('accountNumber',$bank)->get();
                // Log::debug($transaction);
            }
        }
        elseif (!strcmp($toDate,"empty"))
        {
            if(!strcmp($bank,"all"))
            {
                $transaction = BankTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'DESC')->get();
            }
            else
            {
                $transaction = BankTransaction::where('accountNumber',$bank)->whereDate('date','>=',$fromDate)->orderBy('date', 'DESC')->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($bank,"all"))
            {
                $transaction = BankTransaction::whereDate('date','<=',$toDate)->orderBy('date', 'DESC')->get();
            }
            else
            {
                $transaction = BankTransaction::where('accountNumber',$bank)->whereDate('date','<=',$toDate)->orderBy('date', 'DESC')->get();
            }
        }
        else
        {
            if(!strcmp($bank,"all"))
            {
                $transaction = BankTransaction::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'DESC')->get();
            }
            else
            {
                $transaction = BankTransaction::where('accountNumber',$bank)->whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'DESC')->get();
            }
        }
        return Datatables::of($transaction)->make(true);
    }

}
