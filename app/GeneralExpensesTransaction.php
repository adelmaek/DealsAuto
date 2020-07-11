<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class GeneralExpensesTransaction extends Model
{
    public $timestamps = false;

    public static function updateCurrentTotal($dateInput,$typeInput,$valueInput)
    {
        $prevTransaction = GeneralExpensesTransaction::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = GeneralExpensesTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = GeneralExpensesTransaction::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

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
                GeneralExpensesTransaction::where('id', $trans->id)-> update(['currentTotal'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }

    public static function insert_transaction($typeInput, $noteInput, $valueInput,$dateInput)
    {
        $currentTotal = GeneralExpensesTransaction::updateCurrentTotal($dateInput,$typeInput,$valueInput);

        if(!strcmp($typeInput,'add'))
        {
            DB::table('general_expenses_transactions')->insert([
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
            DB::table('general_expenses_transactions')->insert([
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
        $prevTransaction = GeneralExpensesTransaction::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = GeneralExpensesTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = GeneralExpensesTransaction::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
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
            
                GeneralExpensesTransaction::where('id', $trans->id)-> update(['currentTotal'=>$currentBalance]);
        }
    }

    public static function del_transaction($trans_id)
    {
        $transaction = GeneralExpensesTransaction::where('id',$trans_id)->first();
        $transaction->delete();
        GeneralExpensesTransaction::del_updateCurrentTotal($transaction);
    }

}
