<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHookContentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hook_contents', function(Blueprint $table) {
            $table->increments('id');
			$table->string('title',255);
			$table->string('alias',255);
			$table->boolean('show_title')->default(true);
			$table->string('slug', 255);
			$table->text('content');
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
		Schema::drop('hook_contents');
	}

}
