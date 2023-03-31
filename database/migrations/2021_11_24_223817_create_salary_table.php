<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary', function (Blueprint $table) {
            $table->id('id');
            $table->string('month');
            $table->string('year');
            $table->decimal('money', 10, 2);
            $table->decimal('saveMonthly', 10, 2);
            $table->unsignedBigInteger('config_id');
            $table->boolean('passed')->default('0');
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
            $table->foreign('config_id')->references('id')->on('config');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary');
    }
}
