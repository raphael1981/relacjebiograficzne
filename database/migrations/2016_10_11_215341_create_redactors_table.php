<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedactorsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('redactors', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name',100);
			$table->string('surname',100);
			$table->string('email',100)->nullable();
			$table->string('profession',255);
			$table->tinyInteger('status')->default();
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
		Schema::drop('redactors');
	}

}
