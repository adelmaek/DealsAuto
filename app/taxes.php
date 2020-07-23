<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class taxes extends Model
{
    public $timestamps = false;

    public static function updateCurrentTotal_bank ($taxInput, $dateInput, $valueInput,$typeInput)
    {
        $prevTransaction = taxes::where('taxType', $taxInput)->whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = taxes::where('taxType', $taxInput)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = taxes::where('taxType', $taxInput)->whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add") )
                $currentBalanceInput = $prevTransaction->currentBalance +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentBalance -  $valueInput;
        }
        else
        {
            if(!strcmp($typeInput,"add") )
                $currentBalanceInput =  $valueInput;
            else
                $currentBalanceInput =  $valueInput;
        }

        $accumulatedBalance = $currentBalanceInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->action,"add"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            taxes::where('id', $trans->id)-> update(['currentBalance'=>$accumulatedBalance]);
        }
        return $currentBalanceInput;
    }
    public static function insert_transaction($typeInput, $taxInput,$valueInput,$dateInput,$noteInput,$actionInput,$sourceInput)
    {
        $currentBalanceInput = taxes::updateCurrentTotal_bank($taxInput, $dateInput,$valueInput,  $typeInput);
        if(!strcmp($typeInput,"add"))
        {
            DB::table('taxes')->insert([
                'taxType' => $taxInput,
                'date' => $dateInput,
                'type' => "ايداع",
                'value'=> $valueInput,
                'note' => $noteInput,
                'source'=> $sourceInput,
                'currentBalance' => $currentBalanceInput,
                'action' => $actionInput
            ]);
        }
        else
        {
            DB::table('taxes')->insert([
                'taxType' => $taxInput,
                'date' => $dateInput,
                'type' => "سحب",
                'value'=> $valueInput,
                'note' => $noteInput,
                'source'=> $sourceInput,
                'currentBalance' => $currentBalanceInput,
                'action' => $actionInput
            ]);
        }
    }

    public static function del_update_currentTotal($transaction)
    {

        $prevTransaction = taxes::where('taxType', $transaction->taxType)->whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = taxes::where('taxType', $transaction->taxType)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = taxes::where('taxType', $transaction->taxType)->whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentBankBalance;
        else
            $currentBalance =  0;
        foreach($followingTransactions as  $trans)
        {
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            taxes::where('id', $trans->id)-> update(['currentBalance'=>$currentBalance]);
        }
    }

    public static function del_transaction($transaction_id)
    {
        $transaction = taxes::where('id',$transaction_id)->first();
        $transaction->delete();
        taxes::del_update_currentTotal($transaction);
    }
}
