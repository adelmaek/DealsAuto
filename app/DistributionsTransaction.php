<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class DistributionsTransaction extends Model
{
    public $timestamps = false;

    public static function insertion_update_currentTotal($dateInput, $valueInput,$typeInput)
    {
        $prevTransaction = DistributionsTransaction::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = DistributionsTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = DistributionsTransaction::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $prevTransaction->currentDistributionsTotal +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentDistributionsTotal -  $valueInput;
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
                DistributionsTransaction::where('id', $trans->id)-> update(['currentDistributionsTotal'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }
    public static function insert_transaction($partnerInput,$dateInput,$noteInput,$valueInput,$typeInput)
    {
        $currentTotalInput = DistributionsTransaction::insertion_update_currentTotal($dateInput, $valueInput,$typeInput);
        if(!strcmp($typeInput,"add"))
        {
            DB::table('distributions_transactions')->insert([
                'date' => $dateInput,
                'type' => "تغذية",
                'value'=> $valueInput,
                'note' => $noteInput,
                'currentDistributionsTotal' => $currentTotalInput,
                'action' => 'add',
                'partnerName' => $partnerInput
            ]);
        }
        else
        {
            DB::table('distributions_transactions')->insert([
                'date' => $dateInput,
                'type' => "سحب",
                'value'=> $valueInput,
                'note' => $noteInput,
                'currentDistributionsTotal' => $currentTotalInput,
                'action' => 'sub',
                'partnerName' => $partnerInput
            ]);
        }
    }

    public static function deletion_update_current_total($transaction)
    {
        $prevTransaction = DistributionsTransaction::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = DistributionsTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = DistributionsTransaction::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentDistributionsTotal;
        else
            $currentBalance =  0;
        
        foreach($followingTransactions as  $trans)
        {
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            DistributionsTransaction::where('id', $trans->id)-> update(['currentDistributionsTotal'=>$currentBalance]);
        }
    }
    public static function del_transaction($trans_id)
    {
        $transaction = DistributionsTransaction::where('id',$trans_id)->first();
        $transaction->delete();
        DistributionsTransaction::deletion_update_current_total($transaction);
    }
}
