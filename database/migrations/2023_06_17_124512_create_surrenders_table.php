<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurrendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surrenders', function (Blueprint $table) {
            $table->increments('autosurrenderid')->unsigned();
            $table->string('surrender_financialyear')->nullable();
            $table->string('surrender_quarter')->nullable();
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
        Schema::dropIfExists('surrenders');
    }
}
