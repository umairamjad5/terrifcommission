<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->increments('autoledgerid')->unsigned();
            $table->integer('bankquarterlybudgetid')->default(0);
            $table->integer('expenditureid')->default(0);
            $table->string('ledger_financialyear')->nullable();
            $table->string('ledger_quarter')->nullable();
            $table->integer('headcategoryid')->default(0);
            $table->integer('headid')->default(0);
            $table->integer('mainheadid')->default(0);
            $table->integer('subheadid')->default(0);
            $table->string('payable_to')->nullable();
            $table->text('description')->nullable();
            $table->integer('incometax_amount')->default(0);
            $table->integer('gross_amount')->default(0);
            $table->integer('gst')->default(0);
            $table->float('gst_ratio', 10, 4)->default(0);
            $table->integer('debit_amount')->default(0);
            $table->string('payable_from')->nullable();
            $table->text('description_from')->nullable();
            $table->integer('credit_amount')->default(0);
            $table->integer('balance_amount')->default(0);
            $table->integer('last_balance_amount')->default(0);
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
        Schema::dropIfExists('ledgers');
    }
}
