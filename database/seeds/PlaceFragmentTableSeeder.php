<?php

use Illuminate\Database\Seeder;

class PlaceFragmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Entities\Fragment::all() as $f){

            foreach(\App\Entities\Place::all() as $p){

                $random_int = random_int(0, 1);

                if((boolean) $random_int){

                    $f->places()->save($p);

                }

            }

        }
    }
}
