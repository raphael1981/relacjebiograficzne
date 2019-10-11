<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSondasTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sondas', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('customer_id')->unsigned();
			$table->foreign('customer_id')
				->references('id')->on('customers')
				->onDelete('cascade');
			$table->string('trade', 255);
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
		Schema::drop('sondas');
	}

}
