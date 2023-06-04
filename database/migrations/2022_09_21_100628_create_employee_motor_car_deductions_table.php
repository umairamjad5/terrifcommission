<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeMotorCarDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_motor_car_deductions', function (Blueprint $table) {
            $table->increments('autoemployeemotorcardeductionid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->string('motorcar_deduction_month')->nullable();
            $table->integer('motorcar_deduction_amount')->default(0);
            $table->integer('motorcar_deduction_prograssive')->default(0);
            $table->integer('motorcar_deduction_rem_bal')->default(0);
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
        Schema::dropIfExists('employee_motor_car_deductions');
    }
}
