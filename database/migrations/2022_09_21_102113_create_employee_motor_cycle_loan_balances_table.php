<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeMotorCycleLoanBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_motor_cycle_loan_balances', function (Blueprint $table) {
            $table->increments('autoemployeemotorcycleloanbalanceid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->integer('motorcycle_balance_amount')->default(0);
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
        Schema::dropIfExists('employee_motor_cycle_loan_balances');
    }
}
