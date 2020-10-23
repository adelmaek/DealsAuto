<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\SupplierTransaction;
use App\BankTransaction;
class PaymentPaper extends Model
{
    public $timestamps = false;
    protected $fillable = ['state'];
    public static function insertPaper($supplierNameInput,$supplierDateInput,$bankDateInput,$sourceInput,$valueInput,$noteInput)
    {
        DB::table('payment_papers')->insert([
            'supplierName' => $supplierNameInput,
            'note' => $noteInput,
            'creationDate' => $supplierDateInput,
            'settleDate' => $bankDateInput,
            'value' => $valueInput,
            'bankAccountNumber'=>$sourceInput,
        ]);
        SupplierTransaction::insert_transaction($supplierNameInput, $supplierDateInput, "add", $valueInput, $noteInput);
    }
    public static function delPaper($trans_id)
    {
        $paper = PaymentPaper::where('id',$trans_id)->first();
        $paper->delete();
    }
    public static function settlePaper($trans_id)
    {
        $paper = PaymentPaper::where('id',$trans_id)->first();
        $noteInput = $paper->note . " - " . $paper->supplierName;
        $bank = DB::table('banks')->where('accountNumber', $paper->bankAccountNumber)->first();    
        $currencyName = $bank->currency;
        if(!strcmp($currencyName,"egp"))
            $currencyRate = 1;
        else
            $currencyRate = currency::where('name',$currencyName)->first()->rate;
        
        BankTransaction::insert_transaction($paper->bankAccountNumber, 'sub', $paper->settleDate, $paper->value / $currencyRate, $noteInput, $paper->settleDate);
        $paper-> update(['state'=>"settled"]);
    }
}
