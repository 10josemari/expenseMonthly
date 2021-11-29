<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id');
            $table->string('code');
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
        });

        // Añadimos roles
        DB::table('roles')->insert(
            array(
                'code' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )
        );

        DB::table('roles')->insert(
            array(
                'code' => 'observer',
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
        Schema::dropIfExists('roles');
    }
}
