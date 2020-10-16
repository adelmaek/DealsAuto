<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fore_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("note");
            $table->string("action");
            $table->string("type");
            $table->double("value",40,3);
            $table->double("currentTotalBalance",40,3);
            $table->date("date");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fore_payments');
    }
}
