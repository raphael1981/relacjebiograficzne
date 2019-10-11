<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadgablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threadgables', function (Blueprint $table) {
            $table->integer('thread_id')->unsigned()->index();
            $table->foreign('thread_id')->references('id')->on('threads');
            $table->bigInteger('threadgables_id');
            $table->string('threadgables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('threadgables');
    }
}
