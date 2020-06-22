<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankTransaction;
use Illuminate\Http\Request;
use DB;
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
        return view('banks/addBank',['banks'=>$banks]);
    }
    public function getShowBank($accountNumber)
    {
        $transaction = BankTransaction::where('accountNumber',$accountNumber)->get();
        return view('banks/showBank',['transactions'=>$transaction]);
    }
 
}
