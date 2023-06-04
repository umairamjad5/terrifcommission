<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('head_categories', function (Blueprint $table) {
            $table->increments('autoheadcategoryid')->unsigned();
            $table->string('headcategory_financialyear')->nullable();
            $table->string('head_type')->nullable();
            $table->string('head_category_code')->nullable();
            $table->string('head_category_name')->nullable();
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
        Schema::dropIfExists('head_categories');
    }
}
