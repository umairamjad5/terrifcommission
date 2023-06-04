<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('autoemployeeid')->unsigned();
            $table->string('employee_name')->nullable();
            $table->string('employee_designation')->nullable();
            $table->string('employee_scale')->nullable();
            $table->string('date_of_joining')->nullable();
            $table->integer('basic_salary')->default(0);
            $table->integer('personal_salary')->default(0);
            $table->integer('employee_stages')->default(0);
            $table->string('bank_account_detail')->nullable();
            $table->string('date_of_retirement')->nullable();
            $table->integer('retirement_status')->default(0);
            $table->integer('income_tax')->default(0);
            $table->integer('hb_advance')->default(0);
            $table->integer('motorcar_advance')->default(0);
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
        Schema::dropIfExists('employees');
    }
}
