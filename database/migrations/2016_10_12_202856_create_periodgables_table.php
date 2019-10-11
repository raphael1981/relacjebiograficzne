<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodgablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodgables', function (Blueprint $table) {
            $table->integer('period_id')->unsigned()->index();
            $table->foreign('period_id')->references('id')->on('periods');
            $table->bigInteger('periodgables_id');
            $table->string('periodgables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('periodgables');
    }
}
