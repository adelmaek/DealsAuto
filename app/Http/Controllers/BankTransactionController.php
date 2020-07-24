<?php

namespace App\Http\Controllers;
use App\Bank;
use App\BankTransaction;
use App\CashTransaction;
use Illuminate\Http\Request;
use DB;
use Log;
use DataTables;
use App\generalTransaction;
class BankTransactionController extends Controller
{
    public function getCreateTransaction()
    {
        $banks = Bank::all();
        $bankTransactions = BankTransaction::orderBy('date', 'ASC')->get();
        $bankTransactions = generalTransaction::separate_add_from_sub ($bankTransactions);
        return view('transactions/addTransaction',['banks'=>$banks,'transactions'=>$bankTransactions]);
    }
    
    
    public function postCreateTransaction (Request $request)
    {
        // $bank = DB::table('banks')->where('accountNumber', $request-> accountNumberInput)->first();
        
        
        
        // if(!strcmp($request['typeInput'],'addCash'))
        // {
        //     $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $request['dateInput'],$request['valueInput'], 'add');
        //     $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($request['dateInput'],$request['valueInput'], 'add');
        //     CashTransaction::insert_transaction($request['valueInput'],$request['dateInput'],'sub',$request['noteInput'],'normalCash');

        // }
        // elseif(!strcmp($request['typeInput'],'subToNormalCash'))
        // {
        //     $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $request['dateInput'],$request['valueInput'], 'sub');
        //     $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($request['dateInput'],$request['valueInput'], 'sub');
        //     CashTransaction::insert_transaction($request['valueInput'],$request['dateInput'],'add',$request['noteInput'],'normalCash');
        // }   
        // elseif(!strcmp($request['typeInput'],'subToCustodyCash'))
        // {
        //     $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $request['dateInput'],$request['valueInput'], 'sub');
        //     $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($request['dateInput'],$request['valueInput'], 'sub');
        //     CashTransaction::insert_transaction($request['valueInput'],$request['dateInput'],'add',$request['noteInput'],'custodyCash');
        // }
        // else
        // {
        //     $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $request['dateInput'],$request['valueInput'], $request['typeInput']);
        //     $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($request['dateInput'],$request['valueInput'], $request['typeInput']);
        // }

            
       
        // if(!strcmp($request['typeInput'],"add"))
        // {
        //     DB::table('bank_transactions')->insert([
        //         'accountNumber' => $request['accountNumberInput'],
        //         'date' => $request['dateInput'],
        //         'valueDate' => $request['valueDateInput'],
        //         'type' => "ايداع",
        //         'value'=> $request['valueInput'],
        //         'note' => $request['noteInput'],
        //         'bank_id'=> $bank->id,
        //         'currentBankBalance' => $currentBalanceInput,
        //         'currentAllBanksBalance' => $currentAllBalanceInput,
        //         'action' => 'add'
        //     ]);
        //     DB::table('banks')->where('id', $bank-> id)->increment('currentBalance',$request['valueInput']);
        // }
        // else if(!strcmp($request['typeInput'],"sub"))
        // {
        //     DB::table('bank_transactions')->insert([
        //         'accountNumber' => $request['accountNumberInput'],
        //         'date' => $request['dateInput'],
        //         'valueDate' => $request['valueDateInput'],
        //         'type' => "سحب",
        //         'value'=> $request['valueInput'],
        //         'note' => $request['noteInput'],
        //         'bank_id'=> $bank->id,
        //         'currentBankBalance' => $currentBalanceInput,
        //         'currentAllBanksBalance' => $currentAllBalanceInput,
        //         'action'=>'sub'
        //     ]);
        //     DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$request['valueInput']);
        // }
        // elseif(!strcmp($request['typeInput'],'addCash'))
        // {
        //     DB::table('bank_transactions')->insert([
        //         'accountNumber' => $request['accountNumberInput'],
        //         'date' => $request['dateInput'],
        //         'valueDate' => $request['valueDateInput'],
        //         'type' => "ايداع كاش",
        //         'value'=> $request['valueInput'],
        //         'note' => $request['noteInput'],
        //         'bank_id'=> $bank->id,
        //         'currentBankBalance' => $currentBalanceInput,
        //         'currentAllBanksBalance' => $currentAllBalanceInput,
        //         'action' => 'add'
        //     ]);
        //     DB::table('banks')->where('id', $bank-> id)->increment('currentBalance',$request['valueInput']);
        // }
        // elseif(!strcmp($request['typeInput'],'subToNormalCash'))
        // {
        //     DB::table('bank_transactions')->insert([
        //         'accountNumber' => $request['accountNumberInput'],
        //         'date' => $request['dateInput'],
        //         'valueDate' => $request['valueDateInput'],
        //         'type' => "تمويل الخزنة",
        //         'value'=> $request['valueInput'],
        //         'note' => $request['noteInput'],
        //         'bank_id'=> $bank->id,
        //         'currentBankBalance' => $currentBalanceInput,
        //         'currentAllBanksBalance' => $currentAllBalanceInput,
        //         'action' => 'sub'
        //     ]);
        //     DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$request['valueInput']);
        // }
        // elseif(!strcmp($request['typeInput'],'subToCustodyCash'))
        // {
        //     DB::table('bank_transactions')->insert([
        //         'accountNumber' => $request['accountNumberInput'],
        //         'date' => $request['dateInput'],
        //         'valueDate' => $request['valueDateInput'],
        //         'type' => "تمويل العهدة",
        //         'value'=> $request['valueInput'],
        //         'note' => $request['noteInput'],
        //         'bank_id'=> $bank->id,
        //         'currentBankBalance' => $currentBalanceInput,
        //         'currentAllBanksBalance' => $currentAllBalanceInput,
        //         'action' => 'sub'
        //     ]);
        //     DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$request['valueInput']);
        // }
        BankTransaction::insert_transaction($request['accountNumberInput'], $request['typeInput'], $request['dateInput'], $request['valueInput'], $request['noteInput'], $request['valueDateInput']);
        return redirect()->back();
    }
    
    
    
    public function getDelTransaction ($transaction_id, $accNumber)
    {
        BankTransaction::del_transaction($transaction_id,$accNumber);
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
        $transaction = generalTransaction::separate_add_from_sub($transaction);
        return Datatables::of($transaction)->make(true);
    }

}
