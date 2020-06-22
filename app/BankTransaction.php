<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    public $timestamps = false;
    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }
}
