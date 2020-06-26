<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CashTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_transactions', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->double('value',40,3)->default(0);
            $table->string('type');
            $table->date('date');
            $table->string('note');
            $table->double('currentTotal',40,3)->default(0);
            
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
