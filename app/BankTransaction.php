<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $prevTransaction->currentBankBalance +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentBankBalance -  $valueInput;
        }
        else
        {
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $bank->intialBalance + $valueInput;
            else
                $currentBalanceInput = $bank->intialBalance - $valueInput;
        }

        $accumulatedBalance = $currentBalanceInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع"))
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
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $prevTransaction->currentAllBanksBalance +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentAllBanksBalance -  $valueInput;
        }
        else
        {
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $initialBanksBalance + $valueInput;
            else
                $currentBalanceInput = $initialBanksBalance - $valueInput;
        }

        $accumulatedBalance = $currentBalanceInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            BankTransaction::where('id', $trans->id)-> update(['currentAllBanksBalance'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }
}
