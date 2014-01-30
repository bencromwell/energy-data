<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MonthlyAggregate extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly', function (Blueprint $table) {
            $table->tinyInteger('type');
            $table->string('month', 7);
            $table->integer('kwh');

            $table->unique(array('type', 'month'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('monthly');
    }

}
