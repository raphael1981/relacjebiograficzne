<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('terms', function(Blueprint $table) {
            $table->increments('id');
			$table->bigInteger('record_id')->unsigned();
			$table->foreign('record_id')
				->references('id')->on('records')
				->onDelete('cascade');
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
		Schema::drop('terms');
	}

}
