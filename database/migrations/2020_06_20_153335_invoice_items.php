<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('chassisNumber');
            $table->string('color');
            $table->double('unitCost',40,3)->default(0);
            $table->bigInteger('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("invoice_items");
    }
}
