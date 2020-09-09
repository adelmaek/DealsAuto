<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatingExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operating_expenses', function (Blueprint $table) {
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
        Schema::dropIfExists('operating_expenses');
    }
}
