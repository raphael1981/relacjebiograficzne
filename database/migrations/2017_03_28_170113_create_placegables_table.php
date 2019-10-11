<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacegablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('placegables', function (Blueprint $table) {
            $table->integer('place_id')->unsigned()->index();
            $table->foreign('place_id')->references('id')->on('places');
            $table->bigInteger('placegables_id');
            $table->string('placegables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('placegables');
    }
}
