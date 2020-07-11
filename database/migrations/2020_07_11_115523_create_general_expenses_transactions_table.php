<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralExpensesTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_expenses_transactions', function (Blueprint $table) {
            $table->id();
            $table->double('value',40,3);
            $table->string('note');
            $table->double('currentTotal',40,3);
            $table->date('date');
            $table->string('action');
            $table->string('type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_expenses_transactions');
    }
}
