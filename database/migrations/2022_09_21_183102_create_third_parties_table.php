<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThirdPartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_parties', function (Blueprint $table) {
            $table->increments('autothirdpartyid')->unsigned();
            $table->string('third_party_financialyear')->nullable();
            $table->string('third_party_quarter')->nullable();
            $table->integer('headcategoryid')->default(0);
            $table->integer('headid')->default(0);
            $table->integer('mainheadid')->default(0);
            $table->integer('subheadid')->default(0);
            $table->string('bill_no')->nullable();
            $table->string('description')->nullable();
            $table->string('payable_to')->nullable();
            $table->integer('gross_amount')->default(0);
            $table->integer('gst')->default(0);
            $table->float('gst_ratio', 10, 4);
            $table->integer('income_tax')->default(0);
            $table->integer('net_amount')->default(0);
            $table->integer('balance')->default(0);
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
        Schema::dropIfExists('third_parties');
    }
}
