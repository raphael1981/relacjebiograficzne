<?php

use Illuminate\Database\Seeder;

class IntervalFragmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Entities\Fragment::all() as $f){

            foreach(\App\Entities\Interval::all() as $t){

                $random_int = random_int(0, 1);

                if((boolean) $random_int){

                    $f->intervals()->save($t);

                }

            }

        }
    }
}
