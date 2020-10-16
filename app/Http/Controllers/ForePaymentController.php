<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ForePayment;
use App\generalTransaction;
use Log;

class ForePaymentController extends Controller
{
    public function getAddForePayment()
    {
        $transactions = ForePayment::orderBy('date', 'ASC')->get();
        $transactions = generalTransaction::separate_add_from_sub($transactions);
        return view('ForePayments/forePayment',['transactions'=>$transactions]);
    }
    public function postAddForePayment(Request $request)
    {
        Log::debug($request);
        ForePayment::insertTransaction($request['typeInput'],$request['valueInput'],$request['dateInput'],$request['noteInput']);
        return redirect()->back();
    }
    public function getDelForePayment($trans_id)
    {
        ForePayment::delTransaction($trans_id);
        return redirect()->back();
    }
}
