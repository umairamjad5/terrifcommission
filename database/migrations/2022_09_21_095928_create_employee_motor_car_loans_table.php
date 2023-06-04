<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeMotorCarLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_motor_car_loans', function (Blueprint $table) {
            $table->increments('autoemployeemotorcarloanid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->string('motorcar_loan_date')->nullable();
            $table->integer('motorcar_loan_amount')->default(0);
            $table->integer('motorcar_approved_amount')->default(0);
            $table->integer('motorcar_installment_amount')->default(0);
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
        Schema::dropIfExists('employee_motor_car_loans');
    }
}
