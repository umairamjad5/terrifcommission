<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeGPLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_g_p_loans', function (Blueprint $table) {
            $table->increments('autoemployeegploanid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->string('gp_loan_type')->nullable();
            $table->string('gp_loan_date')->nullable();
            $table->integer('gp_loan_amount')->default(0);
            $table->integer('gp_loan_installment')->default(0);
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
        Schema::dropIfExists('employee_g_p_loans');
    }
}
