<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Partner;
use App\PartnerTransaction;
use App\Bank;
use App\CashTransaction;
use App\BankTransaction;
use DataTables;
use Log;
use App\currency;
use App\generalTransaction;
use DB;
class PartnerTransactionController extends Controller
{
    public function getAddPartnerTransaction()
    {
        $partners = Partner::all();
        $transactions = PartnerTransaction::orderBy('date','Asc')->get();
        $banks = Bank::all();
        $transactions = generalTransaction::separate_add_from_sub($transactions);
        return view('partners/addRemoveTransaction',['partners'=>$partners,"transactions"=>$transactions, "banks"=>$banks]);
    }
    
    public function postAddPartnerTransaction(Request $request)
    {
        PartnerTransaction::insert_transaction($request['valueInput'],$request['dateInput'],$request['typeInput'],$request['noteInput'],$request['partnerInput']);
        if(!strcmp($request['sourceInput'],'none'))
        {
            //normal Transaction, do nothing
        }
        elseif(!strcmp($request['sourceInput'],'normalCash'))
        {
            $cashNoteInput = $request['noteInput'] . " - " . $request['partnerInput'];
            if(!strcmp($request['typeInput'],'add'))
                CashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'sub', $cashNoteInput, 'normalCash');
            else
                CashTransaction::insert_transaction($request['valueInput'], $request['dateInput'],'add', $cashNoteInput, 'normalCash');
        }
        elseif(!strcmp($request['sourceInput'],'custodyCash'))
        {
            $cashNoteInput = $request['noteInput'] . " - " . $request['partnerInput'];
            if(!strcmp($request['typeInput'],'add')) 
                CashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'sub', $cashNoteInput, 'custodyCash');
            else
                CashTransaction::insert_transaction($request['valueInput'], $request['dateInput'], 'add', $cashNoteInput, 'custodyCash');

        }
        else
        {
            $noteInput = $request['noteInput'] . " - " . $request['partnerInput'];
            $bank = DB::table('banks')->where('accountNumber', $request['sourceInput'])->first();    
            $currencyName = $bank->currency;
            if(!strcmp($currencyName,"egp"))
                $currencyRate = 1;
            else
                $currencyRate = currency::where('name',$currencyName)->first()->rate;
            if(!strcmp($request['typeInput'],'add'))
                BankTransaction::insert_transaction($request['sourceInput'], 'sub', $request['dateInput'], $request['valueInput'] / $currencyRate, $noteInput, $request['dateInput']);
            else
                BankTransaction::insert_transaction($request['sourceInput'], 'add', $request['dateInput'], $request['valueInput'] / $currencyRate, $noteInput, $request['dateInput']);
        }
        return redirect()->back();
    }

    public function getDelPartnerTransaction($trans_id)
    {
        PartnerTransaction::del_transaction($trans_id);
        return redirect()->back();

    }


    public function getQueryPartnerTransaction()
    {
        $partners = Partner::all();
        return view('partners/queryTransactions',['partners'=>$partners]);
    }

    public function getQueriedPartnerTransactions($partner,$fromDate,$toDate)
    {
        // Log::debug("in partner Query");
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            if(!strcmp($partner,"all"))
            {
                $transaction = PartnerTransaction::orderBy('date', 'ASC')->get();
            }
            else
            {
                // Log::debug($bank);
                $transaction = PartnerTransaction::where('partnerName',$partner)->get();
                // Log::debug($transaction);
            }
        }
        elseif (!strcmp($toDate,"empty"))
        {
            if(!strcmp($partner,"all"))
            {
                $transaction = PartnerTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = PartnerTransaction::where('partnerName',$partner)->whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($partner,"all"))
            {
                $transaction = PartnerTransaction::whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = PartnerTransaction::where('partnerName',$partner)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
        }
        else
        {
            if(!strcmp($partner,"all"))
            {
                $transaction = PartnerTransaction::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {
                $transaction = PartnerTransaction::where('partnerName',$partner)->whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
        }
        $transaction = generalTransaction::separate_add_from_sub($transaction);
        return Datatables::of($transaction)->make(true);
    }

}
