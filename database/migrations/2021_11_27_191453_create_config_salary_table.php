<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_salary', function (Blueprint $table) {
            $table->id('id');
            $table->decimal('bank_previous_month',10,2);
            $table->decimal('bank_adding_savings',10,2);
            $table->decimal('bank_now_total',10,2);
            $table->string('month');
            $table->string('year');
            $table->unsignedBigInteger('salary_id');
            $table->unsignedBigInteger('config_id');
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
            $table->foreign('salary_id')->references('id')->on('salary')->onDelete('cascade');
            $table->foreign('config_id')->references('id')->on('config')->onDelete('cascade');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_salary');
    }
}
