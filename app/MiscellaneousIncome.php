<?php

namespace App;
use DB;
use Log;
use Illuminate\Database\Eloquent\Model;

class MiscellaneousIncome extends Model
{
    public $timestamps = false;
    public static function update_currentTotal($valueInput,$dateInput,$typeInput)
    {
        $prevTransaction = MiscellaneousIncome::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = MiscellaneousIncome::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = MiscellaneousIncome::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add"))
                $currentTotalInput = $prevTransaction->currentTotal +  $valueInput;
            else
                $currentTotalInput = $prevTransaction->currentTotal -  $valueInput;
        }
        else
        {
            $currentTotalInput =  $valueInput;  
        }

        $accumulatedBalance = $currentTotalInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
                MiscellaneousIncome::where('id', $trans->id)-> update(['currentTotal'=>$accumulatedBalance]);
        }

        return $currentTotalInput;
    }
    public static function insert_transaction($valueInput,$dateInput,$typeInput,$noteInput)
    {
        $currentTotalInput = MiscellaneousIncome::update_currentTotal($valueInput,$dateInput,$typeInput);
        if(!strcmp($typeInput,'add'))
        {
            DB::table('miscellaneous_incomes')->insert([
                'value' => $valueInput,
                'date' => $dateInput,
                'type' =>'ايداع',
                'note' => $noteInput,
                'currentTotal'=>$currentTotalInput,
                'action'=>'add'
            ]);
        }
        else
        {
            DB::table('miscellaneous_incomes')->insert([
                'value' => $valueInput,
                'date' => $dateInput,
                'type' =>'سحب',
                'note' => $noteInput,
                'currentTotal'=>$currentTotalInput,
                'action' =>'sub'
            ]);
        }
    }
    public static function del_updateCurrentTotal($transaction)
    {
        $prevTransaction = MiscellaneousIncome::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = MiscellaneousIncome::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = MiscellaneousIncome::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
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
            
                MiscellaneousIncome::where('id', $trans->id)-> update(['currentTotal'=>$currentBalance]);
        }
    }
    public static function del_transaction($MITransaction_id)
    {
        $transaction = MiscellaneousIncome::where('id',$MITransaction_id,)->first();
        $transaction->delete();
        MiscellaneousIncome::del_updateCurrentTotal($transaction);
    }
}
