<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThirdPartyLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_party_ledgers', function (Blueprint $table) {
            $table->increments('autothirdpartyledgerid')->unsigned();
            $table->integer('thirdpartyid')->default(0);
            $table->string('thirdparty_ledger_financialyear')->nullable();
            $table->string('thirdparty_ledger_quarter')->nullable();
            $table->integer('headcategoryid')->default(0);
            $table->integer('headid')->default(0);
            $table->integer('mainheadid')->default(0);
            $table->integer('subheadid')->default(0);
            $table->string('party_employee')->nullable();
            $table->text('particular')->nullable();
            $table->integer('incometax_amount')->default(0);
            $table->integer('gst_amount')->default(0);
            $table->integer('debit_amount')->default(0);
            $table->integer('credit_amount')->default(0);
            $table->integer('balance_amount')->default(0);
            $table->integer('last_balance_amount')->default(0);
            $table->string('amount_date')->nullable();
            $table->string('debit_credit')->nullable();
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
        Schema::dropIfExists('third_party_ledgers');
    }
}
