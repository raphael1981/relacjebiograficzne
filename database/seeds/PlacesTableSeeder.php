<?php

use Illuminate\Database\Seeder;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(App\Entities\Place::class, 60)->create();


        $woj = file_get_contents(base_path().'/public/json/places.json');
        $ob = json_decode($woj);

        foreach($ob as $k=>$w){

            \App\Entities\Place::create([

                'name'=>$w->name,

            ]);

        }

    }
}
