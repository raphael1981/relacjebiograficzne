<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('records', function(Blueprint $table) {
            $table->bigIncrements('id');
			$table->text('title');
			$table->text('alias');
			$table->string('sort_string',500)->nullable();
			$table->string('signature', 255);
			$table->string('source', 1000);
			$table->string('xmltrans', 255);
			$table->mediumText('description');
			$table->mediumText('summary');
			$table->bigInteger('duration');
			$table->enum('type',['video', 'audio']);
			$table->integer('status')->unsigned()->default(0);
			$table->dateTime('published_at')->nullable();
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
		Schema::drop('records');
	}

}
