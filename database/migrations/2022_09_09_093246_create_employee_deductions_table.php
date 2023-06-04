<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_deductions', function (Blueprint $table) {
            $table->increments('autoemployeedeductionid')->unsigned();
            $table->text('financialyear')->nullable();
            $table->text('quarter')->nullable();
            $table->integer('employeeid')->default(0);
            $table->integer('deductionheadid')->default(0);
            $table->text('description')->nullable();
            $table->integer('deduction_amount')->default(0);
            $table->text('deduction_date')->nullable();
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
        Schema::dropIfExists('employee_deductions');
    }
}
