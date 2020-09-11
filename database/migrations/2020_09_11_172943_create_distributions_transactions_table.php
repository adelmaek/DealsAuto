<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributionsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributions_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('partnerName');
            $table->date('date');
            $table->string('note');
            $table->double('value',40,3);
            $table->double('currentDistributionsTotal');
            $table->string('type');
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
        Schema::dropIfExists('distributions_transactions');
    }
}
