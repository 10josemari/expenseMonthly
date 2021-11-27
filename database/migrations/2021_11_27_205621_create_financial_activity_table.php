<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_activity', function (Blueprint $table) {
            $table->id('id');
            $table->string('month');
            $table->string('year');
            $table->string('name');
            $table->string('type');
            $table->decimal('value', 10, 2);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_activity');
    }
}
