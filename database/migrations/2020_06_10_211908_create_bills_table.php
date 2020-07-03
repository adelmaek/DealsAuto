<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number')->unique();
            $table->double('value',40,3)->default(0);
            $table->string('note');
            $table ->string('supplier_name');
            $table->date('date');
            $table->string('type');
            $table->double('addValueTaxes',10,3);
            $table->double('importedTaxes1',10,3)->default(0);
            $table->double('importedTaxes2',10,3)->default(0);
            $table->double('importedTaxes3',10,3)->default(0);
            $table->double('importedTaxes4',10,3)->default(0);
            $table->double('importedTaxes5',10,3)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
