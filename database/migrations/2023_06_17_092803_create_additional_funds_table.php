<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_funds', function (Blueprint $table) {
            $table->increments('autoadditionalfundid')->unsigned();
            $table->string('additionalfund_financialyear')->nullable();
            $table->string('additionalfund_quarter')->nullable();
            $table->integer('headcategoryid')->default(0);
            $table->integer('headid')->default(0);
            $table->integer('mainheadid')->default(0);
            $table->integer('subheadid')->default(0);
            $table->string('date')->nullable();
            $table->integer('amount')->default(0);
            $table->integer('added_by')->default(0);

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
        Schema::dropIfExists('additional_funds');
    }
}
