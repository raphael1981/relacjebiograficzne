<?php

use Illuminate\Database\Seeder;

class IntervalgablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $intervals = \App\Entities\Interval::all();
        $last_key = count($intervals)-1;



        foreach(\App\Entities\Record::skip(10)->take(1000)->get() as $k=>$r){


            foreach(\App\Entities\Record::find($r->id)->fragments()->skip(0)->take(1000)->get() as $y=>$f){

                $random_int = random_int(7, $last_key);

                for($i=0;$i<$random_int;$i++){
                    \App\Entities\Interval::find($intervals[$i]->id)->fragments()->save(\App\Entities\Fragment::find($f->id));
                }

            }


        }
    }
}
