<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class SalesTransaction extends Model
{
    public $timestamps = false;
    
    public static function insertion_update_currentTotal ($dateInput, $valueInput,$typeInput)
    {
        $prevTransaction = SalesTransaction::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = SalesTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = SalesTransaction::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $prevTransaction->currentTotalBalance +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentTotalBalance -  $valueInput;
        }
        else
        {
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput =  $valueInput;
            else
                $currentBalanceInput = $valueInput;
        }

        $accumulatedBalance = $currentBalanceInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->action,"add"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            SalesTransaction::where('id', $trans->id)-> update(['currentTotalBalance'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }
    public static function insertTransaction($typeInput,$valueInput,$dateInput,$noteInput)
    {
        $currentTotalInput = SalesTransaction::insertion_update_currentTotal($dateInput, $valueInput,$typeInput);
        if(!strcmp($typeInput,"add"))
        {
            DB::table('sales_transactions')->insert([
                'date' => $dateInput,
                'type' => "ايداع",
                'value'=> $valueInput,
                'note' => $noteInput,
                'currentTotalBalance' => $currentTotalInput,
                'action' => 'add'
            ]);
        }
        else
        {
            DB::table('sales_transactions')->insert([
                'date' => $dateInput,
                'type' => "سحب",
                'value'=> $valueInput,
                'note' => $noteInput,
                'currentTotalBalance' => $currentTotalInput,
                'action' => 'sub'
            ]);
        }
    }

    public static function deletion_update_current_total($transaction)
    {
        $prevTransaction = SalesTransaction::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = SalesTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = SalesTransaction::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentTotalBalance;
        else
            $currentBalance =  0;
        
        foreach($followingTransactions as  $trans)
        {
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
                SalesTransaction::where('id', $trans->id)-> update(['currentTotalBalance'=>$currentBalance]);
        }
    }

    public static function delTransaction($SalesTransaction_id)
    {
        $transaction = SalesTransaction::where('id',$SalesTransaction_id)->first();
        $transaction->delete();
        SalesTransaction::deletion_update_current_total($transaction);
    }
}
