<?php

namespace App\Http\Controllers;

use App\BankTransaction;
use App\cashTransaction;
use Illuminate\Http\Request;
use App\taxes;
use App\Bank;
use App\generalTransaction;
class TaxesController extends Controller
{
    public function getAddTaxesTrans()
    {
        $taxesTransactiosn = taxes::all();
        $banks = Bank::all();
        foreach($taxesTransactiosn as $trans)
        {
            if(!strcmp($trans->taxType, "addedValue"))
                $trans->taxType = "قيمة مضافة";
            else
                $trans->taxType = "جاري مصلحة الضرايب";
        }
        $transactions = generalTransaction::separate_add_from_sub($taxesTransactiosn);
        return view('taxes/addTaxes',["TaxesTransactions"=>$transactions,"banks"=>$banks]);
    }
    public  function postAddTaxesTrans(Request $request)
    {
        if(!strcmp($request['typeInput'],"add"))
        {
            taxes::insert_transaction($request["typeInput"], $request["taxInput"],$request['valueInput'],$request["dateInput"],$request["noteInput"],"add","none");
        }
        else
        {
            if(!strcmp($request["sourceInput"],"normalCash"))
            {
                taxes::insert_transaction($request["typeInput"], $request["taxInput"],$request['valueInput'],$request["dateInput"],$request["noteInput"],"sub",$request["sourceInput"]);
                cashTransaction::insert_transaction($request['valueInput'], $request["dateInput"], "sub", $request["noteInput"], "normalCash");
            }
            else
            {
                taxes::insert_transaction($request["typeInput"], $request["taxInput"],$request['valueInput'],$request["dateInput"],$request["noteInput"],"sub",$request["sourceInput"]);
                BankTransaction::insert_transaction($request["sourceInput"], "sub", $request["dateInput"], $request['valueInput'], $request["noteInput"], $request["dateInput"]);
            }
        }
        return redirect()->back();
    }
    public function getDelTaxesTrans($trans_id)
    {
        taxes::del_transaction($trans_id);
        return redirect()->back();
    }

    public function getAddedValue()
    {
        $addedValueTrans = taxes::where('taxType', "addedValue" )->orderBy('date',"Asc")->get();
        foreach($addedValueTrans as $trans)
        {
            $trans->taxType = "قيمة مضافة";
        }
        $transactions = generalTransaction::separate_add_from_sub($addedValueTrans);
        return view('taxes/addedValue',["addedValueTrans"=>$transactions]);
    }
    public function getTaxAuth()
    {
        $taxAuthTrans = taxes::where('taxType', "taxAuth" )->orderBy('date',"Asc")->get();
        foreach($taxAuthTrans as $trans)
        {
            $trans->taxType = "جاري مصلحة الضرايب";
        }
        $transactions = generalTransaction::separate_add_from_sub($taxAuthTrans);
        return view('taxes/taxAuth',["taxAuthTrans"=>$transactions]);
    }
}
