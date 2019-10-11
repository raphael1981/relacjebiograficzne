<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitorIndxesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('monitor_indxes', function(Blueprint $table) {
			$table->increments('id');
			$table->enum('type',['records','images','galleries']);
			$table->dateTime('start_at')->nullable();
			$table->dateTime('end_at')->nullable();
			$table->tinyInteger('status')->default(0);
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
		Schema::drop('monitor_indxes');
	}

}
