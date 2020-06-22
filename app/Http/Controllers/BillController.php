<?php

namespace App\Http\Controllers;
use App\Bill;
use App\Supplier;
use App\SupplierTransaction;
use DB;
use Log;
use Illuminate\Http\Request;

class BillController extends Controller
{
   public function getAddNewInvoice()
   {
       $suppliers = Supplier::all();
       $bills = Bill::all();
       return view('Invoices/addNewInvoice',['suppliers'=>$suppliers,'bills'=>$bills]);
   }
   public function postAddNewInvoice(Request $request)
   {
        $totalItemsCost = 0;
        for ($i = 0; $i < count($request['item_name']); $i++)  {
            // Log::debug($request['item_name'][$i]); 

            DB::table('invoice_items')->insert([
                'name' => $request['item_name'][$i],
                'quantity' => $request['item_quantity'][$i],
                'unitCost' =>$request['item_cost'][$i],
                'invoice_number' => $request['invoiceNumberInput']
            ]);
                $totalItemsCost = $totalItemsCost + $request['item_cost'][$i] * $request['item_quantity'][$i];
        }
            Bill::insert([
            'number'=>$request['invoiceNumberInput'],
            'supplier_name'=>$request['supplierInput'],
            'value' =>$totalItemsCost,
            'note' => $request['noteInput'],
            'date' =>$request['dateInput']
        ]);

        //Adding a supplier Transaction
        $supplier = Supplier::where('name', $request['supplierInput'])->first();
        $prevTransaction = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date', '<=', $request['dateInput'])->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date','>',$request['dateInput'])->orderBy('date','Asc')->get();
        if(!empty($prevTransaction))
            $currentSupplierTotalInput = $prevTransaction->currentSupplierTotal - $totalItemsCost;
        else
            $currentSupplierTotalInput = $supplier->initialBalance -$totalItemsCost;

             DB::table('supplier_transactions')->insert([
                'supplier_id'=>$supplier->id,
                'value'=>$totalItemsCost,
                'currentSupplierTotal'=>$currentSupplierTotalInput,
                'note'=>$request['noteInput'],
                'date'=>$request['dateInput'],
                'supplier_name'=>$supplier->name,
                'bill_id'=>  Bill::where('number',$request['invoiceNumberInput'])->first()->id
            ]);
            Supplier::where('name', $request['supplierInput'])->decrement('currentBalance',$totalItemsCost);
            $accumulatedBalance = $currentSupplierTotalInput;
            foreach($followingTransactions as $trans)
            {
                $accumulatedBalance = $accumulatedBalance - $trans->value;
                SupplierTransaction::where('id',$trans->id)->update(['currentSupplierTotal'=>$accumulatedBalance]);
            }
        return redirect()->back();
    }
    public function getDelInvoice($bill_number)
    {
        $bill_id = Bill::where('number',$bill_number)->first()->id;
        Bill::where('number',$bill_number)->delete();

        $items = DB::table('invoice_items')->where('invoice_number',$bill_number)->get();
        // Log::debug($items);
        foreach($items as $item)
            DB::table('invoice_items')->where('id',$item->id)->delete();
        

        //Deleting the trans

        $transaction = SupplierTransaction::where('bill_id',$bill_id)->first();
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
    public function getShowInvoice($bill_number)
    {
        $bill = Bill::where('number',$bill_number)->first();
        $items = DB::table('invoice_items')->where('invoice_number',$bill_number)->get();
        $supplier = Supplier::where('name',$bill->supplier_name)->first();
        return view('Invoices/invoiceDetails',['bill'=>$bill,'billItems'=>$items,'supplier'=>$supplier]);
    }

    public function getQueryInvoice()
    {
        $suppliers = Supplier::all();
        return view('Invoices/queryInvoices',['suppliers'=>$suppliers]);
    }
}
