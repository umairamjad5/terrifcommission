<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_heads', function (Blueprint $table) {
            $table->increments('automainheadid')->unsigned();
            $table->string('mainhead_financialyear')->nullable();
            $table->integer('headcategoryid')->default(0);
            $table->integer('headid')->default(0);
            $table->string('main_head_code')->nullable();
            $table->string('main_head_name')->nullable();
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
        Schema::dropIfExists('main_heads');
    }
}
