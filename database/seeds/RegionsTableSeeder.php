<?php

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $woj = file_get_contents(base_path().'/public/json/woj.json');
        $ob = json_decode($woj);

        foreach($ob as $k=>$w){

            \App\Entities\Region::create([

                'name'=>$w->name,

            ]);

        }
    }
}
