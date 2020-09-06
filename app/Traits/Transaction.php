<?php
 
namespace App\Traits;
use Log;

trait Transaction {
 
    public static function insertion_update_currentTotal() 
    {
        Log::debug('in insertion trait');
    }
    public function deletion_update_currentTotal() 
    {
 
    }
}