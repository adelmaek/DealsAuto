<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Supplier;
use DB;


class SupplierTransaction extends Model
{
    public $timestamps = false;
    public static function separate_add_sub_cols($transactions)
    {
        foreach($transactions as $trans)
        {
            if(!strcmp($trans->type,"sub"))
            {
                $trans->setAttribute("value_add", $trans->value);
                $trans->setAttribute("value_sub", "-");
            }
            else
            {
                $trans->setAttribute("value_add", "-");
                $trans->setAttribute("value_sub", $trans->value);
            }
        }
        return $transactions;
    }
    public static function insert_transaction($supplierNameInput, $dateInput, $typeInput, $valueInput, $noteInput)
    {
        $supplier = Supplier::where('name', $supplierNameInput)->first();
        
        $prevTransaction = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date', '<=', $dateInput)->orderBy('date','Desc')->first();
        if(!empty($prevTransaction))
            $prevTransaction = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date','=',$prevTransaction->date)->orderBy('id','Desc')->first();
        $followingTransactions = SupplierTransaction::where('supplier_id',$supplier->id)->whereDate('date','>',$dateInput)->orderBy('date','Asc')->get();

        if(!empty($prevTransaction))
        {
            if(!strcmp($typeInput,"sub"))
                $currentSupplierTotalInput = $prevTransaction->currentSupplierTotal - $valueInput;
            else
                $currentSupplierTotalInput = $prevTransaction->currentSupplierTotal + $valueInput;
        }     
        else
        {
            if(!strcmp($typeInput,"sub")) 
                 $currentSupplierTotalInput = $supplier->initialBalance - $valueInput;
            else
                $currentSupplierTotalInput = $supplier->initialBalance + $valueInput;
        }
        DB::table('supplier_transactions')->insert([
            'supplier_id'=>$supplier->id,
            'value'=>$valueInput,
            'type' =>$typeInput,
            'currentSupplierTotal'=>$currentSupplierTotalInput,
            'note'=>$noteInput,
            'date'=> $dateInput,
            'supplier_name'=>$supplier->name
        ]);
        if(!strcmp($typeInput,"sub"))  
            Supplier::where('name', $supplierNameInput)->decrement('currentBalance',$valueInput);
        else
            Supplier::where('name', $supplierNameInput)->increment('currentBalance',$valueInput);

        $accumulatedBalance = $currentSupplierTotalInput;
        
        foreach($followingTransactions as $trans)
        {
            if(!strcmp($trans->type,"sub")) 
                $accumulatedBalance = $accumulatedBalance - $trans->value;
            else
                $accumulatedBalance = $accumulatedBalance + $trans->value;
            SupplierTransaction::where('id',$trans->id)->update(['currentSupplierTotal'=>$accumulatedBalance]);
        }
    }
}
