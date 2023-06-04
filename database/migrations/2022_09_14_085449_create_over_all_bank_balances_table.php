<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverAllBankBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('over_all_bank_balances', function (Blueprint $table) {
            $table->increments('autobankquarterlybudgetid')->unsigned();
            $table->integer('bankfinancialyear')->default(0);
            $table->string('bank_quarterly')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_title')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('bank_branch')->nullable();
            $table->integer('bank_quarterly_amount')->default(0);
            $table->integer('bank_quarterly_balance_amount')->default(0);
            $table->string('amount_date')->nullable();
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
        Schema::dropIfExists('over_all_bank_balances');
    }
}
