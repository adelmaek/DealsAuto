<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public static function insert_partner ($nameInput,$intialBalanceInput)
    {
       DB::table('Partners')->insert([
           'name'=>$nameInput,
           'initialBalance'=>$intialBalanceInput,
           'currentBalance'=>$intialBalanceInput
       ]); 
    }
    public static function del_partner($partner_id)
    {
        Partner::where('id',$partner_id)->delete();
    }
}
