<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('articles', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('category_id')->unsigned();
			$table->foreign('category_id')->references('id')->on('categories');
			$table->string('title',1500);
			$table->string('alias',1500);
			$table->string('intro_image',255)->nullable();
			$table->string('disk',25)->nullable();
			$table->text('intro');
			$table->longText('content');
			$table->string('external_url',300)->nullable();
			$table->enum('target_type', ['site','external'])->default('site');
			$table->tinyInteger('featured')->default(0);
			$table->tinyInteger('main')->default(0);
			$table->tinyInteger('status')->default(0);
			$table->dateTime('published_at');
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
		Schema::drop('articles');
	}

}
