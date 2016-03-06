<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class FDaily extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily', function (Blueprint $table) {
            $table->tinyInteger('type');
            $table->date('day');
            $table->decimal('kwh');

            $table->unique(array('type', 'day'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
