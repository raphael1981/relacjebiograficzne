<?php

use Illuminate\Database\Seeder;

class ThreadgablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $periods = \App\Entities\Thread::all();
        $last_key = count($periods)-1;


        foreach(\App\Entities\Record::all() as $k=>$r){

            foreach($periods as $key=>$period){
                \App\Entities\Thread::find($period->id)->records()->save(\App\Entities\Record::find($r->id));
            }

        }
    }
}
