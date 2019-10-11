<?php

use Illuminate\Database\Seeder;

class PlacegablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $places = \App\Entities\Place::all();
        $last_key = count($places)-1;



        foreach(\App\Entities\Record::all() as $k=>$r){

            foreach(\App\Entities\Record::find($r->id)->fragments()->get() as $y=>$f){

                $random_int = random_int(7, $last_key);

                for($i=0;$i<$random_int;$i++){
                    \App\Entities\Place::find($places[$i]->id)->fragments()->save(\App\Entities\Fragment::find($f->id));
                }

            }


        }

    }
}
