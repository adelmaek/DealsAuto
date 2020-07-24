<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankTransaction;
use App\currency;
use Illuminate\Http\Request;
use DB;
use App\generalTransaction;
class BankController extends Controller
{
    //Create and store new bank account
    public function postInsertBank(Request $request)
    {
        $this->validate($request,[
            'accountNumberInput' => 'required',
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
        return view('banks/addBank',['banks'=>$banks,"currencies"=>$currencies]);
    }
    public function getShowBank($accountNumber)
    {
        $transaction = BankTransaction::where('accountNumber',$accountNumber)->get();
        $transaction = generalTransaction::separate_add_from_sub($transaction);
        $transactions = bankTransaction::update_all_banks_total_before_showing($transaction);
        return view('banks/showBank',['transactions'=>$transactions]);
    }
 
}
