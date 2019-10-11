<?php

use Illuminate\Database\Seeder;

class IntervalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(App\Entities\Interval::class, 60)->create();
        $intervals = file_get_contents(url('json/intervals.json'));
        $array = \GuzzleHttp\json_decode($intervals);

        foreach($array as $k=>$intr){

            \App\Entities\Interval::create([
                'name'=>$intr->name,
                'begin'=>$intr->begin,
                'end'=>$intr->end
            ]);

        }
    }
}
