<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class OperatingExpenses extends Model
{
    public $timestamps = false;

    public static function updateCurrentTotal($dateInput,$typeInput,$valueInput)
    {
        $prevTransaction = OperatingExpenses::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = OperatingExpenses::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = OperatingExpenses::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $prevTransaction->currentTotal +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentTotal -  $valueInput;
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
                OperatingExpenses::where('id', $trans->id)-> update(['currentTotal'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }

    public static function insert_transaction($typeInput, $noteInput, $valueInput,$dateInput)
    {
        $currentTotal = OperatingExpenses::updateCurrentTotal($dateInput,$typeInput,$valueInput);

        if(!strcmp($typeInput,'add'))
        {
            DB::table('operating_expenses')->insert([
                'value'=>$valueInput,
                'note'=>$noteInput,
                'date'=>$dateInput,
                'type'=>'ايداع',
                'action'=>'add',
                'currentTotal'=>$currentTotal
            ]);
        }
        else
        {
            DB::table('operating_expenses')->insert([
                'value'=>$valueInput,
                'note'=>$noteInput,
                'date'=>$dateInput,
                'type'=>'سحب',
                'action'=>'sub',
                'currentTotal'=>$currentTotal
            ]);
        }
    }

    public static function del_updateCurrentTotal($transaction)
    {
        $prevTransaction = OperatingExpenses::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = OperatingExpenses::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = OperatingExpenses::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentTotal;
        else
            $currentBalance =  0;
        
        foreach($followingTransactions as  $trans)
        {
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
                OperatingExpenses::where('id', $trans->id)-> update(['currentTotal'=>$currentBalance]);
        }
    }

    public static function del_transaction($trans_id)
    {
        $transaction = OperatingExpenses::where('id',$trans_id)->first();
        $transaction->delete();
        OperatingExpenses::del_updateCurrentTotal($transaction);
    }
}
