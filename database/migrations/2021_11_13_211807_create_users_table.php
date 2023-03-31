<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('username');
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
            $table->dateTime('deleted_at', 0)->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        // Añadimos usuarios
        DB::table('users')->insert(
            array(
                'username' => 'jmgarcia',
                'password' => Hash::make('jm-pass'),
                'role_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )
        );

        DB::table('users')->insert(
            array(
                'username' => 'rosamoreno',
                'password' => Hash::make('rosamoreno'),
                'role_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}