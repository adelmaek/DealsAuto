<?php

namespace App;



class generalTransaction
{
    public static function separate_add_from_sub ($transactions)
    {
        foreach($transactions as $trans)
        {
            if(!strcmp($trans->action,"add"))
            {
                $value_add = number_format((float)$trans->value,2);
                $value_sub = "-";
            }
            else
            {
                $value_add = "-";
                $value_sub = number_format((float)$trans->value,2);
            }

            $trans->setAttribute('value_add',$value_add);
            $trans->setAttribute('value_sub',$value_sub);
        }
        return $transactions;
    }

}
