<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherDeductionStatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_deduction_statments', function (Blueprint $table) {
            $table->increments('autoemployeeotherdeductionstatmentid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->integer('otherdeduction_amount')->default(0);
            $table->integer('otherdeduction_balance')->default(0);
            $table->text('otherdeduction_month')->nullable();
            $table->integer('added_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_deduction_statments');
    }
}
