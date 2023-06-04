<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_allowances', function (Blueprint $table) {
            $table->increments('autoemployeeallowanceid')->unsigned();
            $table->text('financialyear')->nullable();
            $table->text('quarter')->nullable();
            $table->integer('employeeid')->default(0);
            $table->integer('headcategoryid')->default(0);
            $table->integer('headid')->default(0);
            $table->integer('mainheadid')->default(0);
            $table->integer('subheadid')->default(0);
            $table->text('description')->nullable();
            $table->integer('allowance_amount')->default(0);
            $table->text('allowance_date')->nullable();
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
        Schema::dropIfExists('employee_allowances');
    }
}
