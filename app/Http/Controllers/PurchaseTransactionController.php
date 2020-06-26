<?php

namespace App\Http\Controllers;
use App\PurchaseTransaction;
use DB;
use Log;
use Illuminate\Http\Request;
use DataTables;

class PurchaseTransactionController extends Controller
{
    public function getPurchasesTransactions()
    {
        $purchasesTransactions = PurchaseTransaction::orderBy('date','Desc')->get();
        return view('purchases/addRemovePurchaseTransaction',['purchaseTransactions'=>$purchasesTransactions]);
    }

    public function postPurchasesTransactions(Request $request)
    {
        $prevTransaction = PurchaseTransaction::where('type',$request['typeInput'])->whereDate('date', '<=', $request['dateInput'])->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = PurchaseTransaction::where('type',$request['typeInput'])->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = PurchaseTransaction::where('type',$request['typeInput'])->whereDate('date','>',$request['dateInput'])->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
            $currentTotalInput = $prevTransaction->currentTotal + $request['valueInput'];
        else
            $currentTotalInput =  $request['valueInput'];

        
        
        DB::table('purchase_transactions')->insert([
            'type'=>$request['typeInput'],
            'value'=>$request['valueInput'],
            'currentTotal'=>$currentTotalInput,
            'note'=>$request['noteInput'],
            'date'=>$request['dateInput']
        ]);
        

        $accumulatedBalance = $currentTotalInput;
        foreach($followingTransactions as $trans)
        {
            $accumulatedBalance = $accumulatedBalance + $trans->value;
            PurchaseTransaction::where('id',$trans->id)->update(['currentTotal'=>$accumulatedBalance]);
        }
        return redirect()->back();
    }
    public function getDelPurchaseTransaction($trans_id)
    {
        $transaction = PurchaseTransaction::where('id',$trans_id)->first();
        $transaction->delete();

        $prevTransaction = PurchaseTransaction::where('type',$transaction->type)->whereDate('date','<',$transaction->date)->first();
        if(!empty($prevTransaction))
            $prevTransaction = PurchaseTransaction::where('type', $transaction->type)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = PurchaseTransaction::where('type', $transaction->type)->whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentTotal;
        else
            $currentBalance = 0;
        
        foreach($followingTransactions as $trans)
        {
            $currentBalance = $currentBalance + $trans->value;
            PurchaseTransaction::where('id',$trans->id)->update(['currentTotal'=>$currentBalance]);
        }
        
        return redirect()->back();
    }
    public function getQueryPurchaseTransaction()
    {
        return view('purchases/queryPurchasesTransactions');
    }

    public function getQueriedPurchaseTransactions($type,$fromDate,$toDate)
    {
        Log::debug($type);
        Log::debug($fromDate);
        Log::debug($toDate);
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            if(!strcmp($type,"all"))
            {
                $transaction = PurchaseTransaction::orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = PurchaseTransaction::where('type',$type)->get();
            }
                
        }
        elseif (!strcmp($toDate,"empty"))
        {
         
            if(!strcmp($type,"all"))
            {
                $transaction = PurchaseTransaction::whereDate('date','<=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = PurchaseTransaction::where('type',$type)->whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($type,"all"))
            {
                $transaction = PurchaseTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = PurchaseTransaction::where('type',$type)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            
        }
        else
        {
            if(!strcmp($type,"all"))
            {
                $transaction = PurchaseTransaction::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = PurchaseTransaction::where('type',$type)->whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
        }
        foreach($transaction as $trans)
        {
            if(!strcmp($trans->type,'local'))
                $trans->type = 'محلي';
            else
                $trans->type = 'مستورد';
            if($trans->bill_number == -1)
                $trans->bill_number ="لا يوجد";
        }
        return Datatables::of($transaction)->make(true);
    }
    
}
