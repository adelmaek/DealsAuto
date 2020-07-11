<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Partner;
use Log;
class PartnerTransaction extends Model
{
    public $timestamps = false;
    public static function update_currentPartnerTotal($partner,$dateInput,$valueInput,$typeInput)
    {
        $prevTransaction = PartnerTransaction::where('partnerName', $partner->name)->whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = PartnerTransaction::where('partnerName', $partner->name)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = PartnerTransaction::where('partnerName', $partner->name)->whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $prevTransaction->currentPartnerTotal +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentPartnerTotal -  $valueInput;
        }
        else
        {
            Log::debug('here');
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $partner->initialBalance + $valueInput;
            else
                $currentBalanceInput = $partner->initialBalance - $valueInput;
        }

        $accumulatedBalance = $currentBalanceInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->action,"add") )
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
                PartnerTransaction::where('id', $trans->id)-> update(['currentPartnerTotal'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }

    public static function update_currentAllPartnersTotal($dateInput,$valueInput,$typeInput)
    {
        $partners = Partner::all();
        $initialBanksBalance = 0;
        foreach($partners as $partner)
        {
            $initialBanksBalance = $initialBanksBalance + $partner->initialBalance;
        }
        $prevTransaction = PartnerTransaction::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = PartnerTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = PartnerTransaction::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add"))
                $currentBalanceInput = $prevTransaction->currentAllPartnersTotal +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentAllPartnersTotal -  $valueInput;
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
            if(!strcmp($trans->action,"add"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
                PartnerTransaction::where('id', $trans->id)-> update(['currentAllPartnersTotal'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }

    public static function insert_transaction($valueInput,$dateInput,$typeInput,$noteInput,$partnerInput)
    {
        Log::debug($partnerInput);
        
        $partner = Partner::where('name',$partnerInput)->first();
        Log::debug($partner);
        $currentPartnerTotal = PartnerTransaction::update_currentPartnerTotal($partner,$dateInput,$valueInput,$typeInput);
        $currentAllPartnersTotal = PartnerTransaction::update_currentAllPartnersTotal($dateInput,$valueInput,$typeInput);

        if(!strcmp($typeInput,'add'))
        {
            DB::table('partner_transactions')->insert([
                'partnerName' => $partnerInput,
                'note' => $noteInput,
                'type' => 'ايداع',
                'date' => $dateInput,
                'value' => $valueInput,
                'currentPartnerTotal' => $currentPartnerTotal,
                'currentAllPartnersTotal' =>$currentAllPartnersTotal,
                'action' => 'add'
            ]);
            DB::table('partners')->where('id', $partner-> id)->increment('currentBalance',$valueInput);
        }
        else
        {
            DB::table('partner_transactions')->insert([
                'partnerName' => $partnerInput,
                'note' => $noteInput,
                'type' => 'سحب',
                'date' => $dateInput,
                'value' => $valueInput,
                'currentPartnerTotal' => $currentPartnerTotal,
                'currentAllPartnersTotal' =>$currentAllPartnersTotal,
                'action' => 'sub'
            ]);
            DB::table('partners')->where('id', $partner-> id)->decrement('currentBalance',$valueInput);
        }

       
    }

    public static function del_update_currentTotal($transaction)
    {
        $prevTransaction = PartnerTransaction::where('partnerName', $transaction->partnerName)->whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = PartnerTransaction::where('partnerName', $transaction->partnerName)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = PartnerTransaction::where('partnerName', $transaction->partnerName)->whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentPartnerTotal;
        else
            $currentBalance =  DB::table('partners')->where('name', $transaction->partnerName)->first()->initialBalance;
        foreach($followingTransactions as  $trans)
        {
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            PartnerTransaction::where('id', $trans->id)-> update(['currentPartnerTotal'=>$currentBalance]);
        }
    }

    public static function del_update_currentTotal_AllPartners($transaction)
    {
        $partners = Partner::all();
        $initialBanksBalance = 0;
        foreach($partners as $partner)
        {
            $initialBanksBalance = $initialBanksBalance + $partner->initialBalance;
        }
        $prevTransaction = PartnerTransaction::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = PartnerTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = PartnerTransaction::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentAllPartnersTotal;
        else
            $currentBalance =  $initialBanksBalance;
        
        foreach($followingTransactions as  $trans)
        {
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            PartnerTransaction::where('id', $trans->id)-> update(['currentAllPartnersTotal'=>$currentBalance]);
        }
    }
    
    public static function del_transaction($trans_id)
    {
        $transaction = PartnerTransaction::where('id',$trans_id)->first();
        if(!strcmp($transaction->action,"add") )
        {
            DB::table('partners')->where('name', $transaction->partnerName)->decrement('currentBalance',$transaction->value);
        }
        else if(!strcmp($transaction->action,"sub"))
        {
            DB::table('partners')->where('name', $transaction->partnerName)->increment('currentBalance',$transaction->value);
        }
        $transaction->delete();

        PartnerTransaction::del_update_currentTotal($transaction);
        PartnerTransaction::del_update_currentTotal_AllPartners($transaction);
    }
}