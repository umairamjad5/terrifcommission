<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadCategoriesBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('head_categories_budgets', function (Blueprint $table) {
            $table->increments('autoheadcategorybudgetid')->unsigned();
            $table->integer('headcategoryid')->default(0);
            $table->string('head_category_financial_year')->nullable();
            $table->string('head_category_quarter')->nullable();
            $table->integer('head_category_budget_total_amount')->default(0);
            $table->integer('head_category_budget_amount')->default(0);
            $table->integer('head_category_budget_balance')->default(0);
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
        Schema::dropIfExists('head_categories_budgets');
    }
}
