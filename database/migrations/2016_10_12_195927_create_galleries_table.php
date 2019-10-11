<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleriesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('galleries', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name',255);
			$table->string('alias',255);
			$table->text('description');
			$table->text('photos');
			$table->string('regexstamp',100);
			$table->enum('destination', ['gallery', 'article', 'both'])->default('both');
			$table->enum('mode', ['database', 'iptcauthor', 'iptccategory'])->default('database');
			$table->string('disk')->nullable();
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
		Schema::drop('galleries');
	}

}
