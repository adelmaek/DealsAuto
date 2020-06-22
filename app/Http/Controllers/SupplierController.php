<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use App\SupplierTransaction;
use App\Bill;
use DB;
use Log;
class SupplierController extends Controller
{
    public function getSuppliers()
    {
        $suppliers = Supplier::all();
        return view('Suppliers/addDelSupplier',['suppliers'=>$suppliers]);
    }
    public function postSuppliers(Request $request)
    {
        // Log::debug($request['nameInput']);
        // Log::debug($request['totalTransInput']);
        DB::table('suppliers')->insert([
            'name' => $request['nameInput'],
            'initialBalance'=>$request['totalTransInput'],
            'currentBalance'=>$request['totalTransInput']
        ]);
        return redirect()->back();
    }
    public function getDelSupplier($supplier_id)
    {
        Supplier::where('id',$supplier_id,)->first()->delete();
        SupplierTransaction::where('supplier_id',$supplier_id)->delete();
        return redirect()->back();
    }
    public function getShowSupplier($supplier_id)
    {
        
        $Bills = Bill::where([
            ['supplier_id', $supplier_id]
        ])->get();
        return view('Suppliers/showSupplier',['Bills'=>$Bills]);
    }

}
