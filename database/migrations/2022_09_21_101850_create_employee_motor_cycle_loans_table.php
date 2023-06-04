<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeMotorCycleLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_motor_cycle_loans', function (Blueprint $table) {
            $table->increments('autoemployeemotorcycleloanid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->string('motorcycle_loan_date')->nullable();
            $table->integer('motorcycle_loan_amount')->default(0);
            $table->integer('motorcycle_approved_amount')->default(0);
            $table->integer('motorcycle_installment_amount')->default(0);
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
        Schema::dropIfExists('employee_motor_cycle_loans');
    }
}
