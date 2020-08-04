<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
