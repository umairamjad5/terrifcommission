<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionStatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deduction_statments', function (Blueprint $table) {
            $table->increments('autoemployeedeductionstatmentid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->string('month')->nullable();
            $table->integer('income_tax')->default(0);
            $table->integer('ben_fund')->default(0);
            $table->integer('gi_fund')->default(0);
            $table->integer('gp_advance')->default(0);
            $table->integer('hb_advance')->default(0);
            $table->integer('hb_interest')->default(0);
            $table->integer('m_car_adv')->default(0);
            $table->integer('m_cycle_adv')->default(0);
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
        Schema::dropIfExists('deduction_statments');
    }
}
