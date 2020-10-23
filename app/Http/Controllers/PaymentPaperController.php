<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentPaper;
use App\Supplier;
use App\generalTransaction;
use App\Bank;

class PaymentPaperController extends Controller
{
    public function getAddPaper()
    {
        $transactions = PaymentPaper::where('state',"pending")->orderBy('creationDate',"Asc")->get();
        // $transactions = generalTransaction::separate_add_from_sub($transactions);
        $suppliers = Supplier::all();
        $banks = Bank::all();
        $totalPending = 0;
        foreach($transactions as $trans)
        {
            $totalPending += $trans->value;
        }
        return view ("PaymentPapers/addPapper",["transactions"=>$transactions,"suppliers"=>$suppliers,"banks"=>$banks,"totalPending"=>$totalPending]);
    }
    public function postAddPaper(Request $request)
    {
        PaymentPaper::insertPaper($request['supplierNameInput'],$request['supplierDateInput'],$request['bankDateInput'],$request['sourceInput'],$request['valueInput'],$request['noteInput']);
        return redirect()->back();
        
    }
    public function postSettlePaper($trans_id)
    {
        PaymentPaper::settlePaper($trans_id);
        return redirect()->back();
    }
    public function geDelPaper($trans_id)
    {
        PaymentPaper::delPaper($trans_id);
        return redirect()->back();
    }
    public function getSettledPapers()
    {
        $papers = PaymentPaper::where('state',"settled")->orderBy('creationDate',"Asc")->get();
        return view("PaymentPapers/pending",["papers"=>$papers]);
    }

}
