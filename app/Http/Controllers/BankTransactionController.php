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
        $bankTransactions = BankTransaction::orderBy('date', 'ASC')->get();
        return view('transactions/addTransaction',['banks'=>$banks,'transactions'=>$bankTransactions]);
    }
    
    
    public function postCreateTransaction (Request $request)
    {
        $bank = DB::table('banks')->where('accountNumber', $request-> accountNumberInput)->first();
        // $prevTransaction = BankTransaction::where('bank_id', $bank->id)->whereDate('date','<=',$request['dateInput'])->orderBy('date','Desc')->orderBy('id','Desc')->first();
        $prevTransaction = BankTransaction::where('bank_id', $bank->id)->whereDate('date','<=',$request['dateInput'])->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = BankTransaction::where('bank_id', $bank->id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();


        // Log::debug('previous');
        // if(!empty($prevTransaction))
        //     Log::debug($prevTransaction);
        $followingTransactions = BankTransaction::where('bank_id', $bank->id)->whereDate('date','>',$request['dateInput'])->orderBy('date','Asc')->get();
        // Log::debug('following');
        // Log::debug($followingTransactions);


        if(!empty($prevTransaction))
        {
            if(!strcmp($request['typeInput'],"add"))
                $currentBalanceInput = $prevTransaction->currentBankBalance +  $request['valueInput'];
            else
                $currentBalanceInput = $prevTransaction->currentBankBalance -  $request['valueInput'];
        }
        else
        {
            if(!strcmp($request['typeInput'],"add"))
                $currentBalanceInput = $bank->intialBalance + $request['valueInput'];
            else
                $currentBalanceInput = $bank->intialBalance - $request['valueInput'];
        }
            
        // Log::debug('current');
        // Log::debug($currentBalanceInput);

        // if(!strcmp($request['typeInput'],"add"))
        //     $accumulatedBalance = $currentBalanceInput + $request['valueInput'];
        // else
        //     $accumulatedBalance = $currentBalanceInput - $request['valueInput'];

        $accumulatedBalance = $currentBalanceInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            BankTransaction::where('id', $trans->id)-> update(['currentBankBalance'=>$accumulatedBalance]);
        }
        if(!strcmp($request['typeInput'],"add") )
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $request['accountNumberInput'],
                'date' => $request['dateInput'],
                'type' => "ايداع",
                'value'=> $request['valueInput'],
                'note' => $request['noteInput'],
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput
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
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput
            ]);
            DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$request['valueInput']);
        }
        return redirect()->back();
    }
    
    
    
    public function getDelTransaction ($transaction_id, $accNumber)
    {
        $transaction = BankTransaction::where('id',$transaction_id,)->first();
        
        
        if(!strcmp($transaction->type,"ايداع") )
        {
            DB::table('banks')->where('accountNumber', $accNumber)->decrement('currentBalance',$transaction->value);
        }
        else if(!strcmp($transaction->type,"سحب"))
        {
            DB::table('banks')->where('accountNumber', $accNumber)->increment('currentBalance',$transaction->value);
        }
        $transaction->delete();
        $prevTransaction = BankTransaction::where('bank_id', $transaction->bank_id)->whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = BankTransaction::where('bank_id', $transaction->bank_id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = BankTransaction::where('bank_id', $transaction->bank_id)->whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        Log::debug('------------------------------------------------------------');
        Log::debug($prevTransaction);
        Log::debug($followingTransactions);
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentBankBalance;
        else
            $currentBalance =  DB::table('banks')->where('accountNumber', $accNumber)->first()->intialBalance;
        foreach($followingTransactions as  $trans)
        {
            // if( $trans->id < $transaction->id && $trans->date == $transaction->date)
            //     continue;
            if(!strcmp($trans->type,"ايداع"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            BankTransaction::where('id', $trans->id)-> update(['currentBankBalance'=>$currentBalance]);
        }

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
                $transaction = BankTransaction::orderBy('date', 'ASC')->get();
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
                $transaction = BankTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = BankTransaction::where('accountNumber',$bank)->whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($bank,"all"))
            {
                $transaction = BankTransaction::whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = BankTransaction::where('accountNumber',$bank)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
        }
        else
        {
            if(!strcmp($bank,"all"))
            {
                $transaction = BankTransaction::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = BankTransaction::where('accountNumber',$bank)->whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
        }

        return Datatables::of($transaction)->make(true);
    }

}
