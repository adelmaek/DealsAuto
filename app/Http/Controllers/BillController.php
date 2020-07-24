<?php

namespace App\Http\Controllers;
use App\Bill;
use App\Supplier;
use App\SupplierTransaction;
use DB;
use Log;
use Illuminate\Http\Request;
use DataTables;
use App\PurchaseTransaction;
use App\models;
class BillController extends Controller
{
   public function getAddNewInvoice()
   {
       $suppliers = Supplier::all();
       $bills = Bill::all();
       $models= models::getModelsSummary();
       return view('Invoices/addNewInvoice',['suppliers'=>$suppliers,'bills'=>$bills,'models'=>$models]);
   }
   public function postAddNewInvoice(Request $request)
   {
        $totalItemsCost = 0;
        for ($i = 0; $i < count($request['item_name']); $i++)  
        {
            // Log::debug($request['item_name'][$i]); 

            DB::table('invoice_items')->insert([
                'name' => $request['item_name'][$i],
                'chassisNumber' => $request['item_chassis_number'][$i],
                'unitCost' =>$request['item_cost'][$i],
                'invoice_number' => $request['invoiceNumberInput']
            ]);
                // $totalItemsCost = $totalItemsCost + $request['item_cost'][$i] * $request['item_quantity'][$i];
                $totalItemsCost = $totalItemsCost + $request['item_cost'][$i] ;
        }
        if(!strcmp($request['typeInput'],'local'))
            Bill::insert([
                'number'=>$request['invoiceNumberInput'],
                'supplier_name'=>$request['supplierInput'],
                'value' =>$totalItemsCost,
                'note' => $request['noteInput'],
                'date' =>$request['dateInput'],
                'type' =>$request['typeInput'],
                'addValueTaxes' =>$request['addedValueTaxesInput']
            ]);
        else
            Bill::insert([
                'number'=>$request['invoiceNumberInput'],
                'supplier_name'=>$request['supplierInput'],
                'value' =>$totalItemsCost,
                'note' => $request['noteInput'],
                'date' =>$request['dateInput'],
                'type' =>$request['typeInput'],
                'addValueTaxes' =>$request['addedValueTaxesInput'],
                'importedTaxes1' =>$request['importedTaxes1Input'],
                'importedTaxes2' =>$request['importedTaxes2Input'],
                'importedTaxes3' =>$request['importedTaxes3Input'],
                'importedTaxes4' =>$request['importedTaxes4Input'],
                'importedTaxes5' =>$request['importedTaxes5Input']
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

        //Adding a purchasing transaction

        $prevTransaction = PurchaseTransaction::where('type',$request['typeInput'])->whereDate('date', '<=', $request['dateInput'])->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = PurchaseTransaction::where('type',$request['typeInput'])->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = PurchaseTransaction::where('type',$request['typeInput'])->whereDate('date','>',$request['dateInput'])->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
            $currentTotalInput = $prevTransaction->currentTotal + $totalItemsCost;
        else
            $currentTotalInput =  $totalItemsCost;

        
        
        DB::table('purchase_transactions')->insert([
            'type'=>$request['typeInput'],
            'value'=>$totalItemsCost,
            'currentTotal'=>$currentTotalInput,
            'note'=>$request['noteInput'],
            'date'=>$request['dateInput'],
            'bill_number'=>$request['invoiceNumberInput']
        ]);
        

        $accumulatedBalance = $currentTotalInput;
        foreach($followingTransactions as $trans)
        {
            $accumulatedBalance = $accumulatedBalance + $trans->value;
            PurchaseTransaction::where('id',$trans->id)->update(['currentTotal'=>$accumulatedBalance]);
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

        return redirect()->back();
    }

    public function getShowInvoice($bill_number)
    {
        $bill = Bill::where('number',$bill_number)->first();
        $items = DB::table('invoice_items')->where('invoice_number',$bill_number)->get();
        $supplier = Supplier::where('name',$bill->supplier_name)->first();
        $totalWithTaxes = ($bill->value * (($bill->addValueTaxes )/100)) + $bill->value+ $bill->importedTaxes1 + $bill->importedTaxes2 + $bill->importedTaxes3
        + $bill->importedTaxes4 + $bill->importedTaxes5;
        $totalTaxes = ($bill->value *($bill->addValueTaxes / 100)) + $bill->importedTaxes1 + $bill->importedTaxes2 + $bill->importedTaxes3
        + $bill->importedTaxes4 + $bill->importedTaxes5;
        return view('Invoices/invoiceDetails',['bill'=>$bill,'billItems'=>$items,'supplier'=>$supplier,'totalTaxes'=>$totalTaxes,'totalWithTaxes'=>$totalWithTaxes]);
    }

    public function getQueryInvoice()
    {
        $suppliers = Supplier::all();
        return view('Invoices/queryInvoices',['suppliers'=>$suppliers]);
    }


    public function getQueiredInvoices ($supplier,$fromDate,$toDate)
    {
        if(!strcmp($fromDate,"empty")&&!strcmp($toDate,"empty"))
        { 
            if(!strcmp($supplier,"all"))
            {
                $bills = Bill::orderBy('date', 'ASC')->get();
            }
            else
            {   
                $bills = Bill::where('supplier_name',$supplier)->get();
            }
                
        }
        elseif (!strcmp($toDate,"empty"))
        {
            Log::debug('here');
            if(!strcmp($supplier,"all"))
            {
                $bills = Bill::whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $bills = Bill::where('supplier_name',$supplier)->whereDate('date','>=',$fromDate)->orderBy('date', 'ASC')->get();
            }
        }
        elseif (!strcmp($fromDate,"empty"))
        {
            if(!strcmp($supplier,"all"))
            {
                $bills = Bill::whereDate('date','<=',$fromDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $bills = Bill::where('supplier_name',$supplier)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            
        }
        else
        {
            if(!strcmp($supplier,"all"))
            {
                $bills = Bill::whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
            else
            {   
                $bills = Bill::where('supplier_name',$supplier)->whereDate('date','>=',$fromDate)->whereDate('date','<=',$toDate)->orderBy('date', 'ASC')->get();
            }
        }
        foreach($bills as $bill)
        {
            $total_number_items = 0;
            $items = DB::table('invoice_items')->where('invoice_number',$bill->number)->get();
            foreach($items as $item)
            {
                // $total_number_items = $total_number_items + $item->quantity;
                $total_number_items = $total_number_items + 1;
            }
            // $totalTaxesValue = $bill->totalTaxes();
            $totalValueWithTaxes = $bill->totalValueWithTaxes();
        
            $bill->setAttribute('total_items_number', $total_number_items);
            // $bill->setAttribute('totalTaxesValue', $totalTaxesValue);
            $bill->setAttribute('totalValueWithTaxes', $totalValueWithTaxes);
            
        }
        return Datatables::of($bills)->make(true);
    }

    public function getQueryInvoiceTaxes()
    {
        $suppliers = Supplier::all();
        return view('Invoices/queryInvoicesTaxes',['suppliers'=>$suppliers]);
    }
    
}
