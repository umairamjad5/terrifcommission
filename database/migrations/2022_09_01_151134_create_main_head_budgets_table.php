<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainHeadBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_head_budgets', function (Blueprint $table) {
            $table->increments('automainheadbudgetid')->unsigned();
            $table->integer('mainheadid')->default(0);
            $table->string('main_head_financial_year')->nullable();
            $table->string('main_head_quarter')->nullable();
            $table->integer('main_head_budget_total_amount')->default(0);
            $table->integer('main_head_budget_amount')->default(0);
            $table->integer('main_head_budget_balance')->default(0);
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
        Schema::dropIfExists('main_head_budgets');
    }
}
