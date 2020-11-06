<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MiscelAccount;
use DB;
use App\CashTransaction;
use App\BankTransaction;
use App\currency;

class MiscelAccountTransaction extends Model
{
    protected $table = 'miscel_account_transaction';
    public $timestamps = false;
    public static function separate_add_sub_cols($transactions)
    {
        foreach($transactions as $trans)
        {
            if(!strcmp($trans->type,"sub"))
            {
                $trans->setAttribute("value_add", $trans->value);
                $trans->setAttribute("value_sub", "-");
            }
            else
            {
                $trans->setAttribute("value_add", "-");
                $trans->setAttribute("value_sub", $trans->value);
            }
        }
        return $transactions;
    }

    public static function update_current_total_insertion($accountNameInput,$dateInput, $typeInput, $valueInput)
    {
        $account = MiscelAccount::where('name', $accountNameInput)->first();
        
        $prevTransaction = MiscelAccountTransaction::where('account_id',$account->id)->whereDate('date', '<=', $dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = MiscelAccountTransaction::where('account_id',$account->id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = MiscelAccountTransaction::where('account_id',$account->id)->whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"sub"))
                $currentAccountotalInput = $prevTransaction->currentAccountotal - $valueInput;
            else
                $currentAccountotalInput = $prevTransaction->currentAccountotal + $valueInput;
        }     
        else
        {
            if(!strcmp($typeInput,"sub")) 
                 $currentAccountotalInput = $account->initialBalance - $valueInput;
            else
                $currentAccountotalInput = $account->initialBalance + $valueInput;
        }
        $accumulatedBalance = $currentAccountotalInput;
        
        foreach($followingTransactions as $trans)
        {
            if(!strcmp($trans->type,"sub")) 
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance + $trans->value;
                MiscelAccountTransaction::where('id',$trans->id)->update(['currentAccountotal'=>$accumulatedBalance]);
        }
        return $currentAccountotalInput;
    }


    public static function insert_transaction($accountNameInput,$dateInput, $typeInput, $valueInput,$noteInput,$sourceInput)
    {
        $account = MiscelAccount::where('name', $accountNameInput)->first();
        $currentAccountotalInput = MiscelAccountTransaction::update_current_total_insertion($accountNameInput,$dateInput, $typeInput, $valueInput);
        DB::table('miscel_account_transaction')->insert([
            'account_id'=>$account->id,
            'value'=>$valueInput,
            'type' =>$typeInput,
            'currentAccountotal'=>$currentAccountotalInput,
            'note'=>$noteInput,
            'date'=>$dateInput,
            'account_name'=>$account->name
        ]);
        if(!strcmp($typeInput,"sub"))  
            MiscelAccount::where('name', $accountNameInput)->decrement('currentBalance',$valueInput);
        else
            MiscelAccount::where('name', $accountNameInput)->increment('currentBalance',$valueInput);

        //insert transaction to source
        if(!strcmp($typeInput,"add"))
        {
            $cashNoteInput = $noteInput . " - " . $accountNameInput;
            if(!strcmp($sourceInput,"normalCash"))
            {
                CashTransaction::insert_transaction($valueInput,$dateInput, 'sub', $cashNoteInput, 'normalCash');
            }
            else if(!strcmp($sourceInput,"custodyCash"))
            {
                CashTransaction::insert_transaction($valueInput,$dateInput, 'sub', $cashNoteInput, 'custodyCash');
            }
            else if(!strcmp($sourceInput,"none"))
            {
                //Do Nothing
            }
            else
            {
                //bank account
                $noteInput = $noteInput . " - " . $accountNameInput;
                $bank = DB::table('banks')->where('accountNumber', $sourceInput)->first();    
                $currencyName = $bank->currency;
                if(!strcmp($currencyName,"egp"))
                    $currencyRate = 1;
                else
                    $currencyRate = currency::where('name',$currencyName)->first()->rate;
                
                BankTransaction::insert_transaction($sourceInput, 'sub', $dateInput, $valueInput / $currencyRate, $noteInput, $dateInput);
            }
        }
        elseif(!strcmp($typeInput,"sub"))
        {
            $cashNoteInput = $noteInput . " - " . $accountNameInput;
            if(!strcmp($sourceInput,"normalCash"))
            {
                CashTransaction::insert_transaction($valueInput,$dateInput, 'add', $cashNoteInput, 'normalCash');
            }
            else if(!strcmp($sourceInput,"custodyCash"))
            {
                CashTransaction::insert_transaction($valueInput,$dateInput, 'add', $cashNoteInput, 'custodyCash');
            }
            else if(!strcmp($sourceInput,"none"))
            {
                //Do Nothing
            }
            else
            {
                //bank account
                $noteInput = $noteInput . " - " . $accountNameInput;
                $bank = DB::table('banks')->where('accountNumber', $sourceInput)->first();    
                $currencyName = $bank->currency;
                if(!strcmp($currencyName,"egp"))
                    $currencyRate = 1;
                else
                    $currencyRate = currency::where('name',$currencyName)->first()->rate;
                
                BankTransaction::insert_transaction($sourceInput, 'add', $dateInput, $valueInput / $currencyRate, $noteInput, $dateInput);
            }
        }
    }

    public static function del_transaction($transaction_id)
    {
        $transaction = MiscelAccountTransaction::where('id',$transaction_id)->first();
        if(!strcmp($transaction->type,"sub"))
            MiscelAccount::where('id',$transaction->account_id)->increment('currentBalance',$transaction->value);
        else
            MiscelAccount::where('id',$transaction->account_id)->decrement('currentBalance',$transaction->value);
        $transaction->delete();

        $prevTransaction = MiscelAccountTransaction::where('account_id',$transaction->account_id)->whereDate('date','<',$transaction->date)->first();
        if(!empty($prevTransaction))
            $prevTransaction = MiscelAccountTransaction::where('account_id', $transaction->account_id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = MiscelAccountTransaction::where('account_id', $transaction->account_id)->whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentAccountotal;
        else
            $currentBalance = MiscelAccount::where('id',$transaction->account_id)->first()->initialBalance;
        // Log::debug($followingTransactions);
        foreach($followingTransactions as $trans)
        {
            if(!strcmp($trans->type,"sub"))
                $currentBalance = $currentBalance - $trans->value;
            else
            {
                // Log::debug('Here');
                $currentBalance = $currentBalance + $trans->value;
            }
                
            MiscelAccountTransaction::where('id',$trans->id)->update(['currentAccountotal'=>$currentBalance]);
        }
        
        return redirect()->back();
    }
}
