<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReappropiationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reappropiations', function (Blueprint $table) {
            $table->increments('autoreappropiationid')->unsigned();
            $table->string('reappropiation_financialyear')->nullable();
            $table->string('reappropiation_quarter')->nullable();
            $table->integer('fromheadcategoryid')->default(0);
            $table->integer('fromheadid')->default(0);
            $table->integer('frommainheadid')->default(0);
            $table->integer('fromsubheadid')->default(0);
            $table->integer('toheadcategoryid')->default(0);
            $table->integer('toheadid')->default(0);
            $table->integer('tomainheadid')->default(0);
            $table->integer('tosubheadid')->default(0);
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
        Schema::dropIfExists('reappropiations');
    }
}
