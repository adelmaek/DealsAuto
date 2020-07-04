<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class BankTransaction extends Model
{
    public $timestamps = false;
    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }

    public static function updateCurrentTotal_bank ($bank, $dateInput, $valueInput,$typeInput)
    {
        $prevTransaction = BankTransaction::where('bank_id', $bank->id)->whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = BankTransaction::where('bank_id', $bank->id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = BankTransaction::where('bank_id', $bank->id)->whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add") || !strcmp($typeInput,"addCash"))
                $currentBalanceInput = $prevTransaction->currentBankBalance +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentBankBalance -  $valueInput;
        }
        else
        {
            if(!strcmp($typeInput,"add") || !strcmp($typeInput,"addCash"))
                $currentBalanceInput = $bank->intialBalance + $valueInput;
            else
                $currentBalanceInput = $bank->intialBalance - $valueInput;
        }

        $accumulatedBalance = $currentBalanceInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع") || !strcmp($trans->type,"ايداع كاش"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            BankTransaction::where('id', $trans->id)-> update(['currentBankBalance'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }


    public static function updateCurrentTotal_AllBanks ($dateInput, $valueInput,$typeInput)
    {
        $banks = Bank::all();
        $initialBanksBalance = 0;
        foreach($banks as $bank)
        {
            $initialBanksBalance = $initialBanksBalance + $bank->intialBalance;
        }
        $prevTransaction = BankTransaction::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = BankTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = BankTransaction::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add") || !strcmp($typeInput,"addCash"))
                $currentBalanceInput = $prevTransaction->currentAllBanksBalance +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentAllBanksBalance -  $valueInput;
        }
        else
        {
            if(!strcmp($typeInput,"add") || !strcmp($typeInput,"addCash"))
                $currentBalanceInput = $initialBanksBalance + $valueInput;
            else
                $currentBalanceInput = $initialBanksBalance - $valueInput;
        }

        $accumulatedBalance = $currentBalanceInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع") || !strcmp($trans->type,"ايداع كاش"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            BankTransaction::where('id', $trans->id)-> update(['currentAllBanksBalance'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }



    public static function del_update_currentTotal($transaction, $accNumber)
    {
        
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
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            BankTransaction::where('id', $trans->id)-> update(['currentBankBalance'=>$currentBalance]);
        }
    }


    public static function del_update_currentTotal_AllBanks($transaction, $accNumber)
    {
        $banks = Bank::all();
        $initialBanksBalance = 0;
        foreach($banks as $bank)
        {
            $initialBanksBalance = $initialBanksBalance + $bank->intialBalance;
        }
        $prevTransaction = BankTransaction::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = BankTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = BankTransaction::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentAllBanksBalance;
        else
            $currentBalance =  $initialBanksBalance;
        
        foreach($followingTransactions as  $trans)
        {
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            BankTransaction::where('id', $trans->id)-> update(['currentAllBanksBalance'=>$currentBalance]);
        }
    }
    
    
    public static function del_transaction($transaction_id, $accNumber)
    {
        $transaction = BankTransaction::where('id',$transaction_id,)->first();
        if(!strcmp($transaction->action,"add") )
        {
            DB::table('banks')->where('accountNumber', $accNumber)->decrement('currentBalance',$transaction->value);
        }
        else if(!strcmp($transaction->action,"sub"))
        {
            DB::table('banks')->where('accountNumber', $accNumber)->increment('currentBalance',$transaction->value);
        }
        $transaction->delete();

        BankTransaction::del_update_currentTotal($transaction, $accNumber);
        BankTransaction::del_update_currentTotal_AllBanks($transaction, $accNumber);
    }
}
