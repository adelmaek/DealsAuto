<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('accountNumber');
            $table->date('date');
            $table->date('valueDate');
            $table->string('type');
            $table->double('value',40,3);
            $table->string('note');
            $table->integer('bank_id');
            $table->double('currentBankBalance');
            $table->double('currentAllBanksBalance')->default(0);
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
        Schema::dropIfExists('bank_transactions');
    }
}
