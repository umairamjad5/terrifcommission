<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSalaryReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_salary_reports', function (Blueprint $table) {
            $table->increments('autoemployeesalaryreportid')->unsigned();
            $table->string('salary_month')->nullable();
            $table->integer('employeeid')->default(0);
            $table->integer('basic_pay')->default(0);
            $table->integer('qualification_all')->default(0);
            $table->integer('personal_pay')->default(0);
            $table->integer('special_pay')->default(0);
            $table->integer('qualification_pay')->default(0);
            $table->integer('house_rent_all')->default(0);
            $table->integer('conveyance_all')->default(0);
            $table->integer('medical_all')->default(0);
            $table->integer('entertainment_all')->default(0);
            $table->integer('orderly_all')->default(0);
            $table->integer('adhoc_relief')->default(0);
            $table->integer('disparity_reduction_21')->default(0);
            $table->integer('disparity_reduction_22')->default(0);
            $table->integer('medical_charges')->default(0);
            $table->integer('special_all')->default(0);
            $table->integer('utilities')->default(0);
            $table->integer('mobile_phone_all')->default(0);
            $table->integer('monitization_of_residential_telephone')->default(0);
            $table->integer('integrated_all')->default(0);
            $table->integer('additional_charges')->default(0);
            $table->integer('monitization_of_residential_trasport')->default(0);
            $table->integer('income_tax')->default(0);
            $table->integer('ben_fund')->default(0);
            $table->integer('gi_fund')->default(0);
            $table->integer('gpf_adv')->default(0);
            $table->integer('gpf_subs')->default(0);
            $table->integer('hb_interest')->default(0);
            $table->integer('hb_adv')->default(0);
            $table->integer('lfp')->default(0);
            $table->integer('motorcar_interest')->default(0);
            $table->integer('motorcar_adv')->default(0);
            $table->integer('motorcycle_interest')->default(0);
            $table->integer('motorcycle_adv')->default(0);
            $table->integer('others')->default(0);
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
        Schema::dropIfExists('employee_salary_reports');
    }
}
