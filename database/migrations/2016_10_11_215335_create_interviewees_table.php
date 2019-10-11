<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntervieweesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('interviewees', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name',100);
			$table->string('surname',100);
			$table->string('sort_string',500)->nullable();
			$table->mediumText('biography');
			$table->string('portrait',255);
			$table->string('disk',25);
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
		Schema::drop('interviewees');
	}

}
