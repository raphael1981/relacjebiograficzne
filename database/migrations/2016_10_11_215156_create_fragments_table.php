<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFragmentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fragments', function(Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('record_id')->unsigned();
			$table->foreign('record_id')
				->references('id')->on('records')
				->onDelete('cascade');
			$table->longText('content');
			$table->integer('start');
			$table->integer('ord')->unsigned();
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fragments');
	}

}
