<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeOtherDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_other_deductions', function (Blueprint $table) {
            $table->increments('autoemployeeotherdeductionid')->unsigned();
            $table->integer('employeeotherdeductionid')->default(0);
            $table->integer('employeeid')->default(0);
            $table->text('description')->nullable();
            $table->integer('otherdeduction_amount')->default(0);
            $table->integer('otherdeduction_installment')->default(0);
            $table->integer('otherdeduction_balance')->default(0);
            $table->text('otherdeduction_date')->nullable();
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
        Schema::dropIfExists('employee_other_deductions');
    }
}
