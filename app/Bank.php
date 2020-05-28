<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    public function BankTransactions ()
    {
        return $this->hasMany('App\BankTransaction','bank');
    }
}
