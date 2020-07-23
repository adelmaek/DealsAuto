<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
