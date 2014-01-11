<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Prices extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');

            $table->decimal('gas_standing', 6, 4);
            $table->decimal('gas_kwh', 6, 4);
            $table->decimal('electricity_standing', 6, 4);
            $table->decimal('electricity_kwh', 6, 4);

            $table->date('from');
            $table->date('to');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('prices');
    }

}
