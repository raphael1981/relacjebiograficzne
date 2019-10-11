<?php

use Illuminate\Database\Seeder;

class IntervieweesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Entities\Interviewee::class, 20)->create();
    }
}
