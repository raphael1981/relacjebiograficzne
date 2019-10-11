<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntervieweegablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intervieweegables', function (Blueprint $table) {
            $table->integer('interviewee_id')->unsigned()->index();
            $table->foreign('interviewee_id')->references('id')->on('interviewees');
            $table->bigInteger('intervieweegables_id');
            $table->string('intervieweegables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('intervieweegables');
    }
}
