<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeHBLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_h_b_loans', function (Blueprint $table) {
            $table->increments('autoemployeehbloanid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->string('hb_loan_date')->nullable();
            $table->integer('hb_loan_amount')->default(0);
            $table->integer('hb_approved_amount')->default(0);
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
        Schema::dropIfExists('employee_h_b_loans');
    }
}
