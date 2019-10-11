<?php

use Illuminate\Database\Seeder;

class RedactorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Entities\Redactor::class, 50)->create();
    }
}
