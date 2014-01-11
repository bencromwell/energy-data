<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class GasMeta extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gas_meta', function (Blueprint $table) {
            $table->decimal('imperial');
            $table->decimal('calorific_value');
            $table->double('volume_correction');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gas_meta');
    }

}
