<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankTransaction;
use App\currency;
use Illuminate\Http\Request;
use DB;
use Log;
use App\generalTransaction;
class BankController extends Controller
{
    //Create and store new bank account
    public function postInsertBank(Request $request)
    {
        $this->validate($request,[
            'accountNumberInput' => 'required|unique:banks,accountNumber',
            'bankNameInput' => 'required'
        ]);
        DB::table('banks')->insert([
            'accountNumber' => $request['accountNumberInput'],
            'bankName' => $request['bankNameInput'],
            'currentBalance' => $request['balanceInput'],
            'intialBalance' => $request['balanceInput'],
            'currency' => $request['currencyInput']
        ]);
        return redirect()->back();
    }
    public function getAddBank()
    {
        $banks = Bank::all();
        $currencies = currency::all();
        $totalBalances = 0;
        foreach($banks as $bank)
        {
            if(!strcmp($bank->currency,"egp"))
                $currencyRate = 1;
            else
                $currencyRate = currency::where('name',$bank->currency)->first()->rate;
            $totalBalances = $totalBalances + ($bank->currentBalance * $currencyRate);
        }
        // $x = DB::select('select max(id) from bank_transactions where date = (select max(date) from bank_transactions)');
        // Log::debug($x);
        return view('banks/addBank',['banks'=>$banks,"currencies"=>$currencies,'totalBalances'=>$totalBalances]);
    }
    public function getShowBank($accountNumber)
    {
        $transaction = BankTransaction::where('accountNumber',$accountNumber)->orderBy('date','Desc')->get();
        $transaction = generalTransaction::separate_add_from_sub($transaction);
        $transactions = bankTransaction::update_all_banks_total_before_showing($transaction);
        return view('banks/showBank',['transactions'=>$transactions]);
    }
 
}
