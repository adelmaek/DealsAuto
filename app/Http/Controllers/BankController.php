<?php

namespace App\Http\Controllers;

use App\Bank;
use Illuminate\Http\Request;
use DB;

class BankController extends Controller
{
    //Create and store new bank account
    public function postInsertBank(Request $request)
    {
        DB::table('banks')->insert([
            'accountNumber' => $request['accountNumberInput'],
            'bankName' => $request['bankNameInput'],
            'currentBalance' => $request['balanceInput'],
            'currency' => $request['currencyInput']
        ]);
        return redirect()->back();
    }
    public function getAddBank()
    {
        return view('banks/addBank');
    }
}
