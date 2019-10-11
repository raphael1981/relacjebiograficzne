<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturegablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('picturegables', function (Blueprint $table) {
            $table->integer('picture_id')->unsigned()->index();
            $table->foreign('picture_id')->references('id')->on('pictures');
            $table->bigInteger('picturegables_id');
            $table->string('picturegables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('picturegables');
    }
}
