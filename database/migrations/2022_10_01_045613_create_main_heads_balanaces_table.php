<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainHeadsBalanacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_heads_balanaces', function (Blueprint $table) {
            $table->increments('automainheadbalanceid')->unsigned();
            $table->integer('mainheadid')->default(0);
            $table->string('main_head_financial_year')->nullable();
            $table->integer('main_head_budget_total_amount')->default(0);
            $table->integer('main_head_total_amount')->default(0);
            $table->integer('main_head_balance_amount')->default(0);
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
        Schema::dropIfExists('main_heads_balanaces');
    }
}
