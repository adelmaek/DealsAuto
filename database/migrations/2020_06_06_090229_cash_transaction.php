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
            $table->string('name');
            $table->double('value',40,3)->default(0);
            $table->string('type');
            $table->date('date');
            $table->string('note');
            $table->double('currentCashNameTotal',40,3)->default(0);
            $table->double('currentAllCashTotal',40,3)->default(0);
            $table->string('action');
            
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_transactions');
    }
}
