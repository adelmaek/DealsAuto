<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;
class Bill extends Model
{
    public function totalTaxes()
    {
        return  ($this->value *(($this->addValueTaxes)/100)) +$this->importedTaxes1 + $this->importedTaxes2
        + $this->importedTaxes3 + $this->importedTaxes4 + $this->importedTaxes5;
    }
    public function totalValueWithTaxes()
    {
        return $this->value +  ($this->value *(($this->addValueTaxes)/100)) + $this->importedTaxes1 + $this->importedTaxes2
        + $this->importedTaxes3 + $this->importedTaxes4 + $this->importedTaxes5;
    }

    public static function different_total_taxes_for_local_vs_imported($bills)
    {
        foreach($bills as $bill)
        {
            if(!strcmp($bill->type,"local") || !strcmp($bill->type,"used"))
            {
                //el qeema el modafa bas
                $total_local = $bill->value + ($bill->value * ($bill->addValueTaxes/100));
                $bill->setAttribute("local_imported_total",$total_local);
            }
            else if(!strcmp($bill->type,"imported"))
            {
                //every thing not taxes....y3ni kolo m3ada el qeema el modafa
                $total_exported = $bill->value +$bill->importedTaxes1+ $bill->importedTaxes2 +$bill->importedTaxes3 + $bill->importedTaxes4 ;
                $bill->setAttribute("local_imported_total", $total_exported);
            }
            else
                Log::debug('unexpected bill type value');
        }

        return $bills;
    }
    public static function change_addedValue_from_ratio_to_value($bills)
    {
        Log::debug('-----------------');
        Log::debug($bills);
        foreach($bills as $bill)
        {
            Log::debug(($bill->addValueTaxes/100) * $bill->value);
            $bill->addValueTaxes = number_format(($bill->addValueTaxes/100) * $bill->value,3);  
        }
        Log::debug($bills);
        Log::debug('==================');
        return $bills;
    }
}
