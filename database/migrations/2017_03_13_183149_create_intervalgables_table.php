<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntervalgablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intervalgables', function (Blueprint $table) {
            $table->integer('interval_id')->unsigned()->index();
            $table->foreign('interval_id')->references('id')->on('intervals');
            $table->bigInteger('intervalgables_id');
            $table->string('intervalgables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('intervalgables');
    }
}
