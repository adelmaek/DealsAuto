<?php

namespace App\Http\Controllers;
use App\Supplier;
use App\SupplierTransaction;
use DataTables;
use Log;
use DB;
use App\Bill;
use Illuminate\Http\Request;

class SupplierTransactionController extends Controller
{
    public function getInsertTransaction()
    {
        $suppliers = Supplier::all();
        $suppliersTransactions = SupplierTransaction::orderBy('date','Asc')->get();
        return view('Suppliers/addRemoveSupplierTransaction',['suppliers'=>$suppliers,'suppliersTransactions'=>$suppliersTransactions]);
    }
    public function postInsertTransaction(Request $request)
    {
        $supplier = Supplier::where('name', $request['supplierNameInput'])->first();
        Log::debug($supplier);
        $prevTransaction = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date', '<=', $request['dateInput'])->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date','>',$request['dateInput'])->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
            $currentSupplierTotalInput = $prevTransaction->currentSupplierTotal - $request['valueInput'];
        else
            $currentSupplierTotalInput = $supplier->initialBalance - $request['valueInput'];

        
        
        DB::table('supplier_transactions')->insert([
            'supplier_id'=>$supplier->id,
            'value'=>$request['valueInput'],
            'currentSupplierTotal'=>$currentSupplierTotalInput,
            'note'=>$request['noteInput'],
            'date'=>$request['dateInput'],
            'supplier_name'=>$supplier->name
        ]);
        Supplier::where('name', $request['supplierNameInput'])->decrement('currentBalance',$request['valueInput']);

        $accumulatedBalance = $currentSupplierTotalInput;
        foreach($followingTransactions as $trans)
        {
            $accumulatedBalance = $accumulatedBalance - $trans->value;
            SupplierTransaction::where('id',$trans->id)->update(['currentSupplierTotal'=>$accumulatedBalance]);
        }
        return redirect()->back();
    }

    public function getDelTransaction($transaction_id)
    {
        $transaction = SupplierTransaction::where('id',$transaction_id)->first();
        Supplier::where('id',$transaction->supplier_id)->increment('currentBalance',$transaction->value);
        $transaction->delete();
        Log::debug($transaction);

        $prevTransaction = SupplierTransaction::where('supplier_id',$transaction->supplier_id)->whereDate('date','<',$transaction->date)->first();
        if(!empty($prevTransaction))
            $prevTransaction = SupplierTransaction::where('supplier_id', $transaction->supplier_id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = SupplierTransaction::where('supplier_id', $transaction->supplier_id)->whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentSupplierTotal;
        else
            $currentBalance = Supplier::where('id',$transaction->supplier_id)->first()->initialBalance;
        
        foreach($followingTransactions as $trans)
        {
            $currentBalance = $currentBalance - $trans->value;
            SupplierTransaction::where('id',$trans->id)->update(['currentSupplierTotal'=>$currentBalance]);
        }
        
        return redirect()->back();
    }

    public function getQueryTransaction()
    {
        $suppliers = Supplier::all();
        return view('Suppliers/querySupplierTransactions',['suppliers'=>$suppliers]);
    }

    public function getQueriedTransactions($supplier, $fromDate, $toDate)
    {
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            if(!strcmp($supplier,"all"))
            {
                $transaction = SupplierTransaction::orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = SupplierTransaction::where('supplier_id',$supplier)->get();
            }
                
        }
        elseif (!strcmp($toDate,"empty"))
        {
         
            if(!strcmp($supplier,"all"))
            {
                $transaction = SupplierTransaction::whereDate('date','<=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = SupplierTransaction::where('supplier_id',$supplier)->whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($supplier,"all"))
            {
                $transaction = SupplierTransaction::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = SupplierTransaction::where('supplier_id',$supplier)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            
        }
        else
        {
            if(!strcmp($supplier,"all"))
            {
                $transaction = SupplierTransaction::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $transaction = SupplierTransaction::where('supplier_id',$supplier)->whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
        }
        Log::debug($transaction);
        foreach($transaction as $trans)
        {
            if($trans->bill_id == -1)
                $trans->setAttribute('bill_number','لا يوجد');
            else
            {
                $bill = Bill::where('supplier_name',$trans->supplier_name)->first();
                if(!empty($bill))
                    $trans->setAttribute('bill_number',$bill->number);
                else
                    $trans->setAttribute('bill_number',"لا يوجد");
            }
                
        }
        Log::debug($transaction);
        return Datatables::of($transaction)->make(true);
    }
}
