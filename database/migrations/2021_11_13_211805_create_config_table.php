<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->id('id');
            $table->string('option');
            $table->decimal('value', 10, 2);
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
        });

        DB::table('config')->insert(
            array(
                'option' => 'importe inicial',
                'value' => '0.00',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )
        );

        DB::table('config')->insert(
            array(
                'option' => 'ahorro mensual',
                'value' => '0.00',
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
        Schema::dropIfExists('config');
    }
}
