<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClientTransaction;
use App\generalTransaction;

class ClientTransactionController extends Controller
{
    public function getAddClientTrans()
    {
        $clientsTransactions = ClientTransaction::orderBy('date','Asc')->get();
        $clientsTransactions = generalTransaction::separate_add_from_sub($clientsTransactions);
        return view('ClientTransactions/addRemoveTransaction',['clientsTransactions'=>$clientsTransactions]);
    }
    public function postAddClientTrans(Request $request)
    {
        ClientTransaction::insertTransaction($request['typeInput'],$request['valueInput'],$request['dateInput'],$request['noteInput']);
        return redirect()->back();
    }
    public function getDelClientTrans ($clientTransaction_id)
    {
        ClientTransaction::delTransaction($clientTransaction_id);
        return redirect()->back();
    }
}
