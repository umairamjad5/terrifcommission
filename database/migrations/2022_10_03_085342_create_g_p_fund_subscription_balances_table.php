<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGPFundSubscriptionBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_p_fund_subscription_balances', function (Blueprint $table) {
            $table->increments('autoemployeegpsubscriptionbalanceid')->unsigned();
            $table->integer('employeeid')->default(0);
            $table->interger('gp_fund_amount')->default(0);
            $table->integer('gp_fund_balance')->default(0);
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
        Schema::dropIfExists('g_p_fund_subscription_balances');
    }
}
