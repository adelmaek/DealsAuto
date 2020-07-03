<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    public function totalTaxes()
    {
        return $this->value * (($this->addValueTaxes + $this->importedTaxes1 + $this->importedTaxes2
        + $this->importedTaxes3 + $this->importedTaxes4 + $this->importedTaxes5)/100);
    }
    public function totalValueWithTaxes()
    {
        return $this->value + $this->value * (($this->addValueTaxes + $this->importedTaxes1 + $this->importedTaxes2
        + $this->importedTaxes3 + $this->importedTaxes4 + $this->importedTaxes5)/100);
    }
}
