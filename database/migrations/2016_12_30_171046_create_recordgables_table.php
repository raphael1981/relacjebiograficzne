<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordgablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recordgables', function (Blueprint $table) {
            $table->bigInteger('record_id')->unsigned()->index();
            $table->foreign('record_id')->references('id')->on('records');
            $table->bigInteger('recordgables_id');
            $table->string('recordgables_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recordgables');
    }
}
