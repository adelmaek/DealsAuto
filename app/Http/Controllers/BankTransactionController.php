<?php

namespace App\Http\Controllers;
use App\Bank;
use App\BankTransaction;
use App\CashTransaction;
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
        
        $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $request['dateInput'],$request['valueInput'], $request['typeInput']);
        $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($request['dateInput'],$request['valueInput'], $request['typeInput']);
        
        if(!strcmp($request['typeInput'],'addCash'))
            CashTransaction::insert_transaction($request['valueInput'],$request['dateInput'],'sub',$request['noteInput'],'normalCash');
       
        if(!strcmp($request['typeInput'],"add"))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $request['accountNumberInput'],
                'date' => $request['dateInput'],
                'valueDate' => $request['valueDateInput'],
                'type' => "ايداع",
                'value'=> $request['valueInput'],
                'note' => $request['noteInput'],
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput,
                'currentAllBanksBalance' => $currentAllBalanceInput
            ]);
            DB::table('banks')->where('id', $bank-> id)->increment('currentBalance',$request['valueInput']);
        }
        else if(!strcmp($request['typeInput'],"sub"))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $request['accountNumberInput'],
                'date' => $request['dateInput'],
                'valueDate' => $request['valueDateInput'],
                'type' => "سحب",
                'value'=> $request['valueInput'],
                'note' => $request['noteInput'],
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput,
                'currentAllBanksBalance' => $currentAllBalanceInput
            ]);
            DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$request['valueInput']);
        }
        elseif(!strcmp($request['typeInput'],'addCash'))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $request['accountNumberInput'],
                'date' => $request['dateInput'],
                'valueDate' => $request['valueDateInput'],
                'type' => "ايداع كاش",
                'value'=> $request['valueInput'],
                'note' => $request['noteInput'],
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput,
                'currentAllBanksBalance' => $currentAllBalanceInput
            ]);
            DB::table('banks')->where('id', $bank-> id)->increment('currentBalance',$request['valueInput']);
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
