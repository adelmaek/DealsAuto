<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_papers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('supplierName');
            $table->double('value',40,3);
            // $table->double('currentTotal',40,3);
            $table->string('note');
            $table->date('creationDate');
            $table->date('settleDate');
            $table->string('bankAccountNumber');
            // $table->string('type')->default('sub');
            $table->string('state')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_papers');
    }
}
