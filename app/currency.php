<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class currency extends Model
{
    public $timestamps = false;

    public static function insert_new_currency($nameInput,$rateInput)
    {
        DB::table('currencies')->insert([
            "name"=>$nameInput,
            "rate"=>$rateInput
        ]);
    }
    public static function update_currency_rate($nameInput, $rateInput)
    {
        currency::where('name',$nameInput)->update(["rate"=>$rateInput]);
    }
}
