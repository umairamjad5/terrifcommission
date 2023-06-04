<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePayScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_pay_scales', function (Blueprint $table) {
            $table->increments('autoemployeepayscaleid')->unsigned();
            $table->string('payscale_year')->nullable();
            $table->string('bps_scale')->nullable();
            $table->integer('minimum_pay')->default(0);
            $table->integer('increment_amount')->default(0);
            $table->integer('maximum_pay')->default(0);
            $table->integer('scale_stages')->default(0);
            $table->integer('gp_fund_advance')->default(0);
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
        Schema::dropIfExists('employee_pay_scales');
    }
}
