<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiscellaneousIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('miscellaneous_incomes', function (Blueprint $table) {
            $table->id();
            $table->double('value',40,3);
            $table->string('type');
            $table->string('note');
            $table->double('currentTotal',40,3);
            $table->date('date');
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
        Schema::dropIfExists('miscellaneous_incomes');
    }
}
