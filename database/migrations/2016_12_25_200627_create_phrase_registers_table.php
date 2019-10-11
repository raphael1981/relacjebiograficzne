<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhraseRegistersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('phrase_registers', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name', 255);
			$table->bigInteger('count_search');
			$table->ipAddress('visitor_ip');
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
		Schema::drop('phrase_registers');
	}

}
