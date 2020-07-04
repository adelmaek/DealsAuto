<?php

namespace App;
use DB;
use Log;
use Illuminate\Database\Eloquent\Model;

class cashTransaction extends Model
{
    public $timestamps = false;
    public static function insertion_update_currentCashTotal($nameInput,$dateInput,$valueInput,$typeInput)
    {
        $prevTransaction = cashTransaction::where('name', $nameInput)->whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = cashTransaction::where('name', $nameInput)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = cashTransaction::where('name', $nameInput)->whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();


        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $prevTransaction->currentCashNameTotal +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentCashNameTotal -  $valueInput;
        }
        else
        {
 
            $currentBalanceInput =  $valueInput;
        }
        $accumulatedBalance = $currentBalanceInput;
        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            cashTransaction::where('id', $trans->id)-> update(['currentCashNameTotal'=>$accumulatedBalance]);
        }
        return $currentBalanceInput;
    }

    public static function insertion_update_currentAllCashTotal($dateInput,$valueInput,$typeInput)
    {
        $prevTransaction = cashTransaction::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = cashTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = cashTransaction::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();


        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $prevTransaction->currentAllCashTotal +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentAllCashTotal -  $valueInput;
        }
        else
        {
 
            $currentBalanceInput =  $valueInput;
        }
        $accumulatedBalance = $currentBalanceInput;
        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            cashTransaction::where('id', $trans->id)-> update(['currentAllCashTotal'=>$accumulatedBalance]);
        }
        return $currentBalanceInput;
    }

    public static function insert_transaction($valueInput, $dateInput, $typeInput, $noteInput, $nameInput)
    {
        $currentBalanceInput = cashTransaction::insertion_update_currentCashTotal($nameInput, $dateInput, $valueInput, $typeInput);
        $currentAllBalancesInput = cashTransaction::insertion_update_currentAllCashTotal($dateInput, $valueInput, $typeInput);
        Log::debug("$currentAllBalancesInput");
        if(!strcmp($typeInput,"add") )
        {
            Log::debug("add cond");
            DB::table('cash_transactions')->insert([
                'value' => $valueInput,
                'date' => $dateInput,
                'type' => 'ايداع',
                'note' => $noteInput,
                'currentCashNameTotal' =>  $currentBalanceInput,
                'currentAllCashTotal' =>$currentAllBalancesInput,
                'name' => $nameInput
            ]);
        }
        else
        {   
            Log::debug("sub cond");
            DB::table('cash_transactions')->insert([
                'value' => $valueInput,
                'date' => $dateInput,
                'type' => 'سحب',
                'note' => $noteInput,
                'currentCashNameTotal' =>  $currentBalanceInput,
                'currentAllCashTotal' =>$currentAllBalancesInput,
                'name' => $nameInput
            ]);
        }
    }
}
