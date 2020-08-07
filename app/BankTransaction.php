<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use App\PartnerTransaction;
use App\CashTransaction;
use App\MiscellaneousIncome;
use App\currency;
use App\Bank;

class BankTransaction extends Model
{
    public $timestamps = false;
    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }

    public static function updateCurrentTotal_bank ($bank, $dateInput, $valueInput,$typeInput)
    {
        $prevTransaction = BankTransaction::where('bank_id', $bank->id)->whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = BankTransaction::where('bank_id', $bank->id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = BankTransaction::where('bank_id', $bank->id)->whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add") || !strcmp($typeInput,"addCash"))
                $currentBalanceInput = $prevTransaction->currentBankBalance +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentBankBalance -  $valueInput;
        }
        else
        {
            if(!strcmp($typeInput,"add") || !strcmp($typeInput,"addCash"))
                $currentBalanceInput = $bank->intialBalance + $valueInput;
            else
                $currentBalanceInput = $bank->intialBalance - $valueInput;
        }

        $accumulatedBalance = $currentBalanceInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع") || !strcmp($trans->type,"ايداع كاش"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            BankTransaction::where('id', $trans->id)-> update(['currentBankBalance'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }


    public static function updateCurrentTotal_AllBanks ($dateInput, $valueInput,$typeInput)
    {
        $banks = Bank::all();
        $initialBanksBalance = 0;
        foreach($banks as $bank)
        {
            $initialBanksBalance = $initialBanksBalance + $bank->intialBalance;
        }
        $prevTransaction = BankTransaction::whereDate('date','<=',$dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = BankTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = BankTransaction::whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"add") || !strcmp($typeInput,"addCash"))
                $currentBalanceInput = $prevTransaction->currentAllBanksBalance +  $valueInput;
            else
                $currentBalanceInput = $prevTransaction->currentAllBanksBalance -  $valueInput;
        }
        else
        {
            if(!strcmp($typeInput,"add") || !strcmp($typeInput,"addCash"))
                $currentBalanceInput = $initialBanksBalance + $valueInput;
            else
                $currentBalanceInput = $initialBanksBalance - $valueInput;
        }

        $accumulatedBalance = $currentBalanceInput;

        foreach($followingTransactions as $trans)
        {            
            if(!strcmp($trans->type,"ايداع") || !strcmp($trans->type,"ايداع كاش"))
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            BankTransaction::where('id', $trans->id)-> update(['currentAllBanksBalance'=>$accumulatedBalance]);
        }

        return $currentBalanceInput;
    }



    public static function del_update_currentTotal($transaction, $accNumber)
    {

        $prevTransaction = BankTransaction::where('bank_id', $transaction->bank_id)->whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = BankTransaction::where('bank_id', $transaction->bank_id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = BankTransaction::where('bank_id', $transaction->bank_id)->whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentBankBalance;
        else
            $currentBalance =  DB::table('banks')->where('accountNumber', $accNumber)->first()->intialBalance;
        foreach($followingTransactions as  $trans)
        {
            // if( $trans->id < $transaction->id && $trans->date == $transaction->date)
            //     continue;
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + $trans->value;
            else
                $currentBalance = $currentBalance - $trans->value;
            
            BankTransaction::where('id', $trans->id)-> update(['currentBankBalance'=>$currentBalance]);
        }
    }


    public static function del_update_currentTotal_AllBanks($transaction, $accNumber)
    {
        $banks = Bank::all();
        $initialBanksBalance = 0;
        foreach($banks as $bank)
        {
            if(!strcmp($bank->currency,"egp"))
                $currencyRate = 1;
            else
                $currencyRate = currency::where('name',$bank->currency)->first()->rate;
            $initialBanksBalance = $initialBanksBalance + ($bank->intialBalance * $currencyRate);
        }
        $prevTransaction = BankTransaction::whereDate('date','<',$transaction->date)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = BankTransaction::whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = BankTransaction::whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();
        
        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentAllBanksBalance;
        else
            $currentBalance =  $initialBanksBalance;
        
        foreach($followingTransactions as  $trans)
        {
            $bank = Bank::where('accountNumber',$trans->accountNumber)->first();
            if(!strcmp($bank->currency,"egp"))
                $currencyRate = 1;
            else
                $currencyRate = currency::where('name',$bank->currency)->first()->rate;
            if(!strcmp($trans->action,"add"))
                $currentBalance = $currentBalance + ($trans->value * $currencyRate);
            else
                $currentBalance = $currentBalance - ($trans->value * $currencyRate);
            
            BankTransaction::where('id', $trans->id)-> update(['currentAllBanksBalance'=>$currentBalance]);
        }
    }
    
    
    public static function del_transaction($transaction_id, $accNumber)
    {
        $transaction = BankTransaction::where('id',$transaction_id)->first();
        if(!strcmp($transaction->action,"add") )
        {
            DB::table('banks')->where('accountNumber', $accNumber)->decrement('currentBalance',$transaction->value);
        }
        else if(!strcmp($transaction->action,"sub"))
        {
            DB::table('banks')->where('accountNumber', $accNumber)->increment('currentBalance',$transaction->value);
        }
        $transaction->delete();

        BankTransaction::del_update_currentTotal($transaction, $accNumber);
        // BankTransaction::del_update_currentTotal_AllBanks($transaction, $accNumber);
    }



    public static function insert_transaction($accountNumberInput, $typeInput, $dateInput, $valueInput, $noteInput, $valueDateInput)
    {
      
        $bank = DB::table('banks')->where('accountNumber', $accountNumberInput)->first();    
        $currencyName = $bank->currency;
        if(!strcmp($currencyName,"egp"))
            $currencyRate = 1;
        else
            $currencyRate = currency::where('name',$currencyName)->first()->rate;
            
        if(!strcmp($typeInput,'addCash'))
        {
            $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $dateInput,$valueInput, 'add');
            // $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($dateInput,$valueInput, 'add');
            CashTransaction::insert_transaction($valueInput * $currencyRate,$dateInput,'sub',$noteInput,'normalCash');

        }
        elseif(!strcmp($typeInput,'subToNormalCash'))
        {
            $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $dateInput,$valueInput, 'sub');
            // $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($dateInput,$valueInput, 'sub');
            CashTransaction::insert_transaction($valueInput * $currencyRate ,$dateInput,'add',$noteInput,'normalCash');
        }   
        elseif(!strcmp($typeInput,'subToCustodyCash'))
        {
            $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $dateInput,$valueInput, 'sub');
            // $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($dateInput,$valueInput, 'sub');
            CashTransaction::insert_transaction($valueInput * $currencyRate,$dateInput,'add',$noteInput,'custodyCash');
        }
        elseif(!strcmp($typeInput,'personalSub'))
        {
            $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $dateInput,$valueInput, 'sub');
            // $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($dateInput,$valueInput, 'sub');
            // PartnerTransaction::insert_transaction($valueInput,$dateInput,'add',$noteInput);
        }
        elseif(!strcmp($typeInput, 'debtorInterest'))
        {
            $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $dateInput,$valueInput, 'sub');
            // $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($dateInput,$valueInput, 'sub');
            MiscellaneousIncome::insert_transaction($valueInput * $currencyRate,$dateInput,'add',$noteInput);
        }
        else
        {
            $currentBalanceInput = BankTransaction::updateCurrentTotal_bank($bank, $dateInput,$valueInput,  $typeInput);
            // $currentAllBalanceInput = BankTransaction::updateCurrentTotal_AllBanks($dateInput,$valueInput,  $typeInput);
        }

            
       
        if(!strcmp($typeInput,"add"))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $accountNumberInput,
                'date' => $dateInput,
                'valueDate' => $valueDateInput,
                'type' => "ايداع",
                'value'=> $valueInput,
                'note' => $noteInput,
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput,
                'currentAllBanksBalance' =>0,
                'action' => 'add'
            ]);
            DB::table('banks')->where('id', $bank-> id)->increment('currentBalance',$valueInput);
        }
        else if(!strcmp($typeInput,"sub"))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $accountNumberInput,
                'date' => $dateInput,
                'valueDate' => $valueDateInput,
                'type' => "سحب",
                'value'=> $valueInput,
                'note' => $noteInput,
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput,
                'currentAllBanksBalance' => 0,
                'action'=>'sub'
            ]);
            DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$valueInput);
        }
        elseif(!strcmp($typeInput,'addCash'))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $accountNumberInput,
                'date' => $dateInput,
                'valueDate' => $valueDateInput,
                'type' => "ايداع كاش",
                'value'=> $valueInput,
                'note' => $noteInput,
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput,
                'currentAllBanksBalance' => 0,
                'action' => 'add'
            ]);
            DB::table('banks')->where('id', $bank-> id)->increment('currentBalance',$valueInput);
        }
        elseif(!strcmp($typeInput,'subToNormalCash'))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $accountNumberInput,
                'date' => $dateInput,
                'valueDate' => $valueDateInput,
                'type' => "تمويل الخزنة",
                'value'=> $valueInput,
                'note' => $noteInput,
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput,
                'currentAllBanksBalance' => 0,
                'action' => 'sub'
            ]);
            DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$valueInput);
        }
        elseif(!strcmp($typeInput,'subToCustodyCash'))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $accountNumberInput,
                'date' => $dateInput,
                'valueDate' => $valueDateInput,
                'type' => "تمويل العهدة",
                'value'=> $valueInput,
                'note' => $noteInput,
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput,
                'currentAllBanksBalance' => 0,
                'action' => 'sub'
            ]);
            DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$valueInput);
        }
        elseif(!strcmp($typeInput,'personalSub'))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $accountNumberInput,
                'date' => $dateInput,
                'valueDate' => $valueDateInput,
                'type' => "مسحوبات شخصية",
                'value'=> $valueInput,
                'note' => $noteInput,
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput,
                'currentAllBanksBalance' => 0,
                'action' => 'sub'
            ]);
            DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$valueInput);
        }
        elseif(!strcmp($typeInput,'debtorInterest'))
        {
            DB::table('bank_transactions')->insert([
                'accountNumber' => $accountNumberInput,
                'date' => $dateInput,
                'valueDate' => $valueDateInput,
                'type' => "فايدة مدينة",
                'value'=> $valueInput,
                'note' => $noteInput,
                'bank_id'=> $bank->id,
                'currentBankBalance' => $currentBalanceInput,
                'currentAllBanksBalance' => 0,
                'action' => 'add'
            ]);
            DB::table('banks')->where('id', $bank-> id)->increment('currentBalance',$valueInput);
        }
    }
    public static function update_all_banks_total_before_showing($transactions)
    {
        $total =0;
        $banks = Bank::all();
        foreach($banks as $bank)
        {
            if(!strcmp($bank->currency,"egp"))
                $currencyRate = 1;
            else
                $currencyRate = currency::where('name',$bank->currency)->first()->rate;
            $total = $total + $bank->intialBalance * $currencyRate;
        }
        foreach($transactions as $trans)
        {
            $bank = Bank::where("id", $trans->bank_id)->first();
            if(!strcmp($bank->currency,"egp"))
                $currencyRate = 1;
            else
                $currencyRate = currency::where('name',$bank->currency)->first()->rate;
            if(!strcmp($trans->action, "add"))
            {
                $total = $total + $trans->value * $currencyRate;
            }
            else
            {
                $total = $total - $trans->value * $currencyRate;
            }
            $trans->currentAllBanksBalance = $total;
        }
        return $transactions;
    }

    public static function add_currency_field($transactions)
    {
        foreach($transactions as $trans)
        {
            $currency = Bank::where("id", $trans->bank_id)->first()->currency;
            $trans->setAttribute('currency',$currency);
        }
        return $transactions;
    }
}
