<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreatePiggyBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('piggy_bank', function (Blueprint $table) {
            $table->id('id');
            $table->string('option');
            $table->string('amount')->default(0);
            $table->dateTime('created_at', 0);
            $table->dateTime('updated_at', 0);
        });

        $moneys = array("0.01","0.02","0.05","0.10","0.20","0.50","1.00","2.00");
        foreach($moneys as $money){
            DB::table('piggy_bank')->insert(
                array(
                    'option' => $money,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                )
            );
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('piggy_bank');
    }
}
