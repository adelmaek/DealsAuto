<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MiscellaneousIncome;
use DB;
use Log;
use App\generalTransaction;
class MiscellaneousIncomeController extends Controller
{
    public function getMITransactions()
    {
        $MITransactions = MiscellaneousIncome::all();
        $transaction = generalTransaction::separate_add_from_sub($MITransactions);
        return view('MiscellaneousIncome/addRemoveMiscellaneousIncome',['MITransactions'=>$transaction]);
    }
    public function postMITransactions(Request $request)
    {
        MiscellaneousIncome::insert_transaction($request['valueInput'],$request['dateInput'],$request['typeInput'],$request['noteInput']);
        return redirect()->back();
    }
    
    public function getDelMITransaction($MITransaction_id)
    {
        MiscellaneousIncome::del_transaction($MITransaction_id);
        return redirect()->back();
    }
}
