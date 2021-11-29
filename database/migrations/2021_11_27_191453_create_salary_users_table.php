<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_users', function (Blueprint $table) {
            $table->id('id');
            $table->decimal('amount',10,2);
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
        Schema::dropIfExists('salary_users');
    }
}
