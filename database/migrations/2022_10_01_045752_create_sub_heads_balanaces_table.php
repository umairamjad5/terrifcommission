<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubHeadsBalanacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_heads_balanaces', function (Blueprint $table) {
            $table->increments('autosubheadbalanceid')->unsigned();
            $table->integer('subheadid')->default(0);
            $table->string('sub_head_financial_year')->nullable();
            $table->integer('sub_head_budget_total_amount')->default(0);
            $table->integer('sub_head_total_amount')->default(0);
            $table->integer('sub_head_balance_amount')->default(0);
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
        Schema::dropIfExists('sub_heads_balanaces');
    }
}
