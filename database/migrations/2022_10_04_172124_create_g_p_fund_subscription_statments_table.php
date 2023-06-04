<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGPFundSubscriptionStatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_p_fund_subscription_statments', function (Blueprint $table) {
            $table->increments('autoemployeegploanstatmentid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->string('gp_fund_statment_month')->nullable();
            $table->integer('monthly_subscription')->default(0);
            $table->integer('refund_installment_amount')->default(0);
            $table->integer('contributed_by_amount')->default(0);
            $table->integer('transfer_amount')->default(0);
            $table->integer('widrawl_amount')->default(0);
            $table->integer('progressive_balance')->default(0);
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
        Schema::dropIfExists('g_p_fund_subscription_statments');
    }
}
