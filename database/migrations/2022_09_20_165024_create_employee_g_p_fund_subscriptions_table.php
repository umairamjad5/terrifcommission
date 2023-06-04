<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeGPFundSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_g_p_fund_subscriptions', function (Blueprint $table) {
            $table->increments('autoemployeegpsubscriptionid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->string('gp_fund_month')->nullable();
            $table->integer('gp_fund_amount')->default(0);
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
        Schema::dropIfExists('employee_g_p_fund_subscriptions');
    }
}
