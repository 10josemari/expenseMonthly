<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlySavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_savings', function (Blueprint $table) {
            $table->id('id');
            $table->string('month');
            $table->string('year');
            $table->decimal('value', 10, 2);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('config_id');
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
            $table->dateTime('deleted_at', 0)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('monthly_savings');
    }
}
