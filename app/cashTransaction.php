<?php

namespace App;
use DB;
use Log;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    public $timestamps = false;
    public static function insertion_update_currentCashTotal($nameInput,$dateInput,$valueInput,$typeInput)
    {
        $prevTransaction = CashTransaction::where('name', $nameInput)->whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = CashTransaction::where('name', $nameInput)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = CashTransaction::where('name', $nameInput)->whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();


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
            CashTransaction::where('id', $trans->id)-> update(['currentCashNameTotal'=>$accumulatedBalance]);
        }
        return $currentBalanceInput;
    }

    public static function insertion_update_currentAllCashTotal($dateInput,$valueInput,$typeInput)
    {
        $prevTransaction = CashTransaction::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = CashTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = CashTransaction::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();


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
            CashTransaction::where('id', $trans->id)-> update(['currentAllCashTotal'=>$accumulatedBalance]);
        }
        return $currentBalanceInput;
    }

    public static function insert_transaction($valueInput, $dateInput, $typeInput, $noteInput, $nameInput)
    {
        $currentBalanceInput = CashTransaction::insertion_update_currentCashTotal($nameInput, $dateInput, $valueInput, $typeInput);
        $currentAllBalancesInput = CashTransaction::insertion_update_currentAllCashTotal($dateInput, $valueInput, $typeInput);
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
                'name' => $nameInput,
                'action' => 'add'
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
                'name' => $nameInput,
                'action' => 'sub'
            ]);
        }
    }


    public static function del_update_currentCashTotal($transaction)
    {
        $prevTransaction = CashTransaction::where('name', $transaction->name)->whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = CashTransaction::where('name', $transaction->name)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = CashTransaction::where('name', $transaction->name)->whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentCashNameTotal;
        else
            $currentBalance =  0;
        foreach($followingTransactions as  $trans)
        {
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            CashTransaction::where('id', $trans->id)-> update(['currentCashNameTotal'=>$currentBalance]);
        }
    }
    public static function del_update_currentAllCashesTotal($transaction)
    {
        $prevTransaction = CashTransaction::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = CashTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = CashTransaction::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentAllCashTotal;
        else
            $currentBalance =  0;
        foreach($followingTransactions as  $trans)
        {
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            CashTransaction::where('id', $trans->id)-> update(['currentAllCashTotal'=>$currentBalance]);
        }
    }
    public static function del_transaction($CashTransaction_id)
    {
        $transaction = CashTransaction::where('id',$CashTransaction_id)->first();
        $transaction->delete();

        CashTransaction::del_update_currentCashTotal($transaction);
        CashTransaction::del_update_currentAllCashesTotal($transaction);
        
    }
}
