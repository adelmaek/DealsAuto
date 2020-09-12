<?php

namespace App\Http\Controllers;
use App\Supplier;
use App\SupplierTransaction;
use DataTables;
use Log;
use DB;
use App\Bill;
use Illuminate\Http\Request;
use App\Bank;
use App\BankTransaction;
use App\cashTransaction;
use App\currency;
class SupplierTransactionController extends Controller
{
    public function getInsertTransaction()
    {
        $suppliers = Supplier::all();
        $suppliersTransactions = SupplierTransaction::orderBy('date','Asc')->get();
        $suppliersTransactions = SupplierTransaction::separate_add_sub_cols($suppliersTransactions);
        $banks = Bank::all();
        
        return view('Suppliers/addRemoveSupplierTransaction',['suppliers'=>$suppliers,'suppliersTransactions'=>$suppliersTransactions,'banks'=>$banks]);
    }
    public function postInsertTransaction(Request $request)
    {
        $supplier = Supplier::where('name', $request['supplierNameInput'])->first();
        
        $prevTransaction = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date', '<=', $request['dateInput'])->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date','>',$request['dateInput'])->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($request['typeInput'],"sub"))
                $currentSupplierTotalInput = $prevTransaction->currentSupplierTotal - $request['valueInput'];
            else
                $currentSupplierTotalInput = $prevTransaction->currentSupplierTotal + $request['valueInput'];
        }     
        else
        {
            if(!strcmp($request['typeInput'],"sub")) 
                 $currentSupplierTotalInput = $supplier->initialBalance - $request['valueInput'];
            else
                $currentSupplierTotalInput = $supplier->initialBalance + $request['valueInput'];
        }
        DB::table('supplier_transactions')->insert([
            'supplier_id'=>$supplier->id,
            'value'=>$request['valueInput'],
            'type' =>$request['typeInput'],
            'currentSupplierTotal'=>$currentSupplierTotalInput,
            'note'=>$request['noteInput'],
            'date'=>$request['dateInput'],
            'supplier_name'=>$supplier->name
        ]);
        if(!strcmp($request['typeInput'],"sub"))  
            Supplier::where('name', $request['supplierNameInput'])->decrement('currentBalance',$request['valueInput']);
        else
            Supplier::where('name', $request['supplierNameInput'])->increment('currentBalance',$request['valueInput']);

        $accumulatedBalance = $currentSupplierTotalInput;
        
        foreach($followingTransactions as $trans)
        {
            if(!strcmp($trans->type,"sub")) 
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            SupplierTransaction::where('id',$trans->id)->update(['currentSupplierTotal'=>$accumulatedBalance]);
        }
        //insert transaction to source
        if(!strcmp($request['typeInput'],"add"))
        {
            $cashNoteInput = $request['noteInput'] . " - " . $request['supplierNameInput'];
            if(!strcmp($request['sourceInput'],"normalCash"))
            {
                cashTransaction::insert_transaction($request['valueInput'],$request['dateInput'], 'sub', $cashNoteInput, 'normalCash');
            }
            else if(!strcmp($request['sourceInput'],"custodyCash"))
            {
                cashTransaction::insert_transaction($request['valueInput'],$request['dateInput'], 'sub', $cashNoteInput, 'custodyCash');
            }
            else if(!strcmp($request['sourceInput'],"none"))
            {
                //Do Nothing
            }
            else
            {
                //bank account
                $noteInput = $request['noteInput'] . " - " . $request['supplierNameInput'];
                $bank = DB::table('banks')->where('accountNumber', $request['sourceInput'])->first();    
                $currencyName = $bank->currency;
                if(!strcmp($currencyName,"egp"))
                    $currencyRate = 1;
                else
                    $currencyRate = currency::where('name',$currencyName)->first()->rate;
                
                BankTransaction::insert_transaction($request['sourceInput'], 'sub', $request['dateInput'], $request['valueInput'] / $currencyRate, $noteInput, $request['dateInput']);
            }
        }
        return redirect()->back();
    }

    public function getDelTransaction($transaction_id)
    {
        $transaction = SupplierTransaction::where('id',$transaction_id)->first();
        if(!strcmp($transaction->type,"sub"))
            Supplier::where('id',$transaction->supplier_id)->increment('currentBalance',$transaction->value);
        else
            Supplier::where('id',$transaction->supplier_id)->decrement('currentBalance',$transaction->value);
        $transaction->delete();

        $prevTransaction = SupplierTransaction::where('supplier_id',$transaction->supplier_id)->whereDate('date','<',$transaction->date)->first();
        if(!empty($prevTransaction))
            $prevTransaction = SupplierTransaction::where('supplier_id', $transaction->supplier_id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();

        $followingTransactions = SupplierTransaction::where('supplier_id', $transaction->supplier_id)->whereDate('date','>=',$transaction->date)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
            $currentBalance = $prevTransaction->currentSupplierTotal;
        else
            $currentBalance = Supplier::where('id',$transaction->supplier_id)->first()->initialBalance;
        Log::debug($followingTransactions);
        foreach($followingTransactions as $trans)
        {
            if(!strcmp($trans->type,"sub"))
                $currentBalance = $currentBalance - $trans->value;
            else
            {
                Log::debug('Here');
                $currentBalance = $currentBalance + $trans->value;
            }
                
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
        $transaction = SupplierTransaction::separate_add_sub_cols($transaction);
        return Datatables::of($transaction)->make(true);
    }
}
