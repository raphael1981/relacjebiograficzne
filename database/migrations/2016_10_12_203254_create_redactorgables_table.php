<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedactorgablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redactorgables', function (Blueprint $table) {
            $table->integer('redactor_id')->unsigned()->index();
            $table->foreign('redactor_id')->references('id')->on('redactors');
            $table->bigInteger('redactorgables_id');
            $table->string('redactorgables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('redactorgables');
    }
}
