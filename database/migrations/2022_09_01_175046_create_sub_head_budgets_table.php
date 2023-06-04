<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubHeadBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_head_budgets', function (Blueprint $table) {
            $table->increments('autosubheadbudgetid')->unsigned();
            $table->integer('subheadid')->default(0);
            $table->string('sub_head_financial_year')->nullable();
            $table->string('sub_head_quarter')->nullable();
            $table->integer('sub_head_budget_total_amount')->default(0);
            $table->integer('sub_head_budget_amount')->default(0);
            $table->integer('sub_head_budget_balance')->default(0);
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
        Schema::dropIfExists('sub_head_budgets');
    }
}
