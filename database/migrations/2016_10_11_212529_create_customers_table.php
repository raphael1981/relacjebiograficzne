<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table) {
            $table->increments('id');
//			$table->integer('region_id')->unsigned();
//			$table->foreign('region_id')
//				->references('id')->on('regions');
			$table->string('name',100);
			$table->string('surname',100);
			$table->string('email')->unique();
			$table->string('password');
			$table->string('phone',100);
			$table->enum('customer_type', ['osoba prywatna','instytucja'])->default('osoba prywatna');
			$table->string('institution_name',150);
			$table->string('register_target',255);
			$table->tinyInteger('status')->default(-1);
			$table->string('verification_token',255)->nullable();
			$table->string('remember_token',255)->nullable();
			$table->dateTime('expire_remember_token')->nullable();
			$table->softDeletes();
            $table->timestamps();
		});

		Schema::connection('mysqlbackup')->create('customers', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name',100);
			$table->string('surname',100);
			$table->string('email')->unique();
			$table->string('password');
			$table->string('phone',100);
			$table->enum('customer_type', ['osoba prywatna','instytucja'])->default('osoba prywatna');
			$table->string('institution_name',150);
			$table->string('register_target',255);
			$table->tinyInteger('status')->default(-1);
			$table->string('verification_token',255)->nullable();
			$table->string('remember_token',255)->nullable();
			$table->dateTime('expire_remember_token')->nullable();
			$table->softDeletes();
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
		Schema::drop('customers');
	}

}
