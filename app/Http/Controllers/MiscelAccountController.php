<?php

namespace App\Http\Controllers;

use App\MiscelAccount;
use Illuminate\Http\Request;
use App\MiscelAccountTransaction;
use DB;
class MiscelAccountController extends Controller
{
    public function getAddAccounts()
    {
        $accounts = MiscelAccount::all(); 
        return view("MiscellaneousAccounts\addAccount",["accounts"=>$accounts]);
    }
    public function postAddAccount(Request $request)
    {
        DB::table('miscel_accounts')->insert([
            'name' => $request['nameInput'],
            'initialBalance'=>$request['totalTransInput'],
            'currentBalance'=>$request['totalTransInput']
        ]);
        return redirect()->back();
    }
    public function getDelAccount($account_id)
    {
        MiscelAccount::where('id',$account_id)->first()->delete();
        MiscelAccountTransaction::where('account_id',$account_id)->delete();
        return redirect()->back();
    }
    
}
