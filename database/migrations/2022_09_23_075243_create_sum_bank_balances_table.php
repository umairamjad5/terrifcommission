<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSumBankBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sum_bank_balances', function (Blueprint $table) {
            $table->increments('autobanksumbudgetid')->unsigned();
            $table->string('bankfinancialyear')->nullable();
            $table->integer('bank_sum_amount')->default(0);
            $table->integer('bank_sum_balance_amount')->default(0);
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
        Schema::dropIfExists('sum_bank_balances');
    }
}
