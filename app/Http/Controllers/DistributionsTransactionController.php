<?php

namespace App\Http\Controllers;

use App\DistributionsTransaction;
use Illuminate\Http\Request;
use App\Partner;
use App\Bank;
use App\generalTransaction;
use Illuminate\Support\Facades\Redis;
use App\BankTransaction;
use App\currency;
use DB;
use App\CashTransaction;

class DistributionsTransactionController extends Controller
{
    public function getAddDistributionsTransaction()
    {
        $partners = Partner::all();
        $transactions = DistributionsTransaction::orderBy('date','Asc')->get();
        $banks = Bank::all();
        $transactions = generalTransaction::separate_add_from_sub($transactions);
        return view('distributions/addRemoveTrans',['partners'=>$partners,"transactions"=>$transactions, "banks"=>$banks]);
    }
    public function postAddDistributionsTransaction(Request $request)
    {
        if(!strcmp($request['typeInput'],"add"))
        {
            DistributionsTransaction::insert_transaction($request["partnerInput"],$request["dateInput"],$request["noteInput"],$request["valueInput"],'add');
            if(!strcmp($request['sourceInput'],'normalCash') || !strcmp($request['sourceInput'],'custodyCash'))
            {
                $noteInput = $request['noteInput'] . " - تغذية التوزيعات";
                if(!strcmp($request['sourceInput'],'normalCash'))
                    CashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'sub', $noteInput, 'normalCash');
                else if (!strcmp($request['sourceInput'],'custodyCash'))
                    CashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'sub', $noteInput, 'custodyCash');
            }
            else if(!strcmp($request['sourceInput'],'none'))
            {
                //do nothing
            }
            else
            {
                // a bank transaction
                $bank = DB::table('banks')->where('accountNumber', $request['sourceInput'])->first();    
                $currencyName = $bank->currency;
                if(!strcmp($currencyName,"egp"))
                    $currencyRate = 1;
                else
                    $currencyRate = currency::where('name',$currencyName)->first()->rate;
                $noteInput = $request['noteInput'] . " - تغذية التوزيعات";
                BankTransaction::insert_transaction($request['sourceInput'], 'sub', $request['dateInput'], $request['valueInput'] / $currencyRate, $noteInput, $request['dateInput']);
            }
        }
        else
        {
            DistributionsTransaction::insert_transaction($request["partnerInput"],$request["dateInput"],$request["noteInput"],$request["valueInput"],'sub');
        }      
        return redirect()->back();
    }
    public function getDelDistributionsTransaction($trans_id)
    {
        DistributionsTransaction::del_transaction($trans_id);
        return redirect()->back();
    }
}
