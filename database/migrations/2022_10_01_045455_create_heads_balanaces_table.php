<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadsBalanacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heads_balanaces', function (Blueprint $table) {
            $table->increments('autoheadbalanceid')->unsigned();
            $table->integer('headid')->default(0);
            $table->string('head_financial_year')->nullable();
            $table->integer('head_budget_total_amount')->default(0);
            $table->integer('head_total_amount')->default(0);
            $table->integer('head_balance_amount')->default(0);
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
        Schema::dropIfExists('heads_balanaces');
    }
}
