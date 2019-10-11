<?php

use Illuminate\Database\Seeder;

class TagFragmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Entities\Fragment::all() as $f){

            foreach(\App\Entities\Tag::all() as $t){

                $random_int = random_int(0, 1);

                if((boolean) $random_int){

                    $f->tags()->save($t);

                }

            }

        }
    }
}
