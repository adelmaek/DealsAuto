<?php

namespace App\Http\Controllers;
use App\Bank;
use App\BankTransaction;
use Illuminate\Http\Request;
use DB;
class BankTransactionController extends Controller
{
    public function getCreateTransaction()
    {
        $banks = Bank::all();
        $bankTransactions = BankTransaction::all();
        return view('transactions/addTransaction',['banks'=>$banks,'transactions'=>$bankTransactions]);
    }
    public function postCreateTransaction (Request $request)
    {
        $bank = DB::table('banks')->where('accountNumber', $request-> accountNumberInput)->first();
        DB::table('bank_transactions')->insert([
            'accountNumber' => $request['accountNumberInput'],
            'date' => $request['dateInput'],
            'type' => $request['typeInput'],
            'value'=> $request['valueInput'],
            'note' => $request['noteInput'],
            'bank_id'=> $bank->id
        ]);
        if(!strcmp($request['typeInput'],"add") )
        {
            DB::table('banks')->where('id', $bank-> id)->increment('currentBalance',$request['valueInput']);
        }
        else if(!strcmp($request['typeInput'],"sub"))
        {
            DB::table('banks')->where('id', $bank-> id)->decrement('currentBalance',$request['valueInput']);
        }
        return redirect()->back();
    }
    public function getDelTransaction ($transaction_id, $accNumber)
    {
        $transaction = BankTransaction::where('id',$transaction_id,)->first();
        // $bank = DB::table('banks')->where('accountNumber', $request-> accountNumberInput)->first();
        if(!strcmp($transaction->type,"add") )
        {
            DB::table('banks')->where('accountNumber', $accNumber)->decrement('currentBalance',$transaction->value);
        }
        else if(!strcmp($transaction->type,"sub"))
        {
            DB::table('banks')->where('accountNumber', $accNumber)->increment('currentBalance',$transaction->value);
        }
        
        $transaction->delete();
        
        return redirect()->back();
    }
}
