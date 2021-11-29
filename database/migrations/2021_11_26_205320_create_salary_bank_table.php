<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_bank', function (Blueprint $table) {
            $table->id('id');
            $table->decimal('bank_previous_month',10,2);
            $table->decimal('bank_adding_savings',10,2);
            $table->decimal('bank_now_total',10,2);
            $table->unsignedBigInteger('salary_id');
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
            $table->foreign('salary_id')->references('id')->on('salary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_bank');
    }
}
