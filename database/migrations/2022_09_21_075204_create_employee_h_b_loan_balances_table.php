<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeHBLoanBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_h_b_loan_balances', function (Blueprint $table) {
            $table->increments('autoemployeehbloanbalanceid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->integer('hb_balance_amount')->default(0);
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
        Schema::dropIfExists('employee_h_b_loan_balances');
    }
}
