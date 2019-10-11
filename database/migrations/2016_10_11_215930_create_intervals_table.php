<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntervalsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('intervals', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name',500)->nullable();
			$table->string('alias',500)->nullable();
			$table->date('begin')->nullable();
			$table->date('end')->nullable();
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
		Schema::drop('intervals');
	}

}
