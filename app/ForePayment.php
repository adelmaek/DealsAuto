<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class ForePayment extends Model
{
    public $timestamps = false;
    
    public static function insertion_update_currentTotal ($dateInput, $valueInput,$typeInput)
    {
        $prevTransaction = ForePayment::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = ForePayment::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = ForePayment::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

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
            ForePayment::where('id', $trans->id)-> update(['currentTotalBalance'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }
    public static function insertTransaction($typeInput,$valueInput,$dateInput,$noteInput)
    {
        $currentTotalInput = ForePayment::insertion_update_currentTotal($dateInput, $valueInput,$typeInput);
        if(!strcmp($typeInput,"add"))
        {
            DB::table('fore_payments')->insert([
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
            DB::table('fore_payments')->insert([
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
        $prevTransaction = ForePayment::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = ForePayment::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = ForePayment::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
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
            
            ForePayment::where('id', $trans->id)-> update(['currentTotalBalance'=>$currentBalance]);
        }
    }

    public static function delTransaction($trans_id)
    {
        $transaction = ForePayment::where('id',$trans_id)->first();
        $transaction->delete();
        ForePayment::deletion_update_current_total($transaction);
    }
}
