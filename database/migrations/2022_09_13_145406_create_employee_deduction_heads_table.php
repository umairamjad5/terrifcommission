<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDeductionHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_deduction_heads', function (Blueprint $table) {
            $table->increments('autoemployeedeductionheadid')->unsigned();
            $table->string('deductionhead_financialyear')->nullable();
            $table->string('deduction_head_code')->nullable();
            $table->string('deduction_head_name')->nullable();
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
        Schema::dropIfExists('employee_deduction_heads');
    }
}
