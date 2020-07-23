<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
class models extends Model
{
    public static function getModelsSummary()
    {
        $models = DB::select("SELECT MODL_ID , MODL_NAME, BRND_NAME, MODL_YEAR, MODL_CATG, MODL_ACTV 
        FROM models, brands
        WHERE MODL_BRND_ID = BRND_ID
        AND   MODL_ACTV=1
        ORDER BY BRND_NAME ASC , MODL_NAME ASC");

        return $models;
    }
}
