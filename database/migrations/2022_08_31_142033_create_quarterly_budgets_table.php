<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuarterlyBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quarterly_budgets', function (Blueprint $table) {
            $table->increments('autoquarterlybudgetid')->unsigned();
            $table->integer('financialyear')->default(0);
            $table->string('quarterly')->nullable();
            $table->integer('quarterly_amount')->default(0);
            $table->integer('quarterly_balance_amount')->default(0);
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
        Schema::dropIfExists('quarterly_budgets');
    }
}
