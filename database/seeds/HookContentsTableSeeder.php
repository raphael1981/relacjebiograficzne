<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class HookContentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();


        $h1 = \App\Entities\HookContent::create([
            'title'=>'Zapraszamy',
            'alias'=>str_slug('Zapraszamy'),
            'show_title'=>false,
            'slug'=>'home-about',
            'content'=>file_get_contents(public_path().'/html/intro-site.html'),
            'status'=>1
        ]);

        \App\Entities\HookContent::find($h1->id)->update(['slug'=>$h1->id.'-'.$h1->alias]);

        $h2 = \App\Entities\HookContent::create([
            'title'=>'PROJEKT',
            'alias'=>str_slug('PROJEKT'),
            'show_title'=>true,
            'slug'=>'about-project',
            'content'=>file_get_contents(public_path().'/html/about-project.html'),
            'status'=>1
        ]);

        \App\Entities\HookContent::find($h2->id)->update(['slug'=>$h2->id.'-'.$h2->alias]);

        $h3 = \App\Entities\HookContent::create([
            'title'=>'Regulamin portalu relacjebiograficzne.pl',
            'alias'=>str_slug('Regulamin portalu relacjebiograficzne.pl'),
            'show_title'=>true,
            'slug'=>'regulamin',
            'content'=>file_get_contents(public_path().'/html/regulamin.html'),
            'status'=>1
        ]);


        \App\Entities\HookContent::find($h3->id)->update(['slug'=>$h3->id.'-'.$h3->alias]);

        $h4 = \App\Entities\HookContent::create([
            'title'=>'Polityka prywatności serwisu www.relacjebiograficzne.pl',
            'alias'=>str_slug('Polityka prywatności serwisu www.relacjebiograficzne.pl'),
            'show_title'=>true,
            'slug'=>'private-police',
            'content'=>file_get_contents(public_path().'/html/polityka_prywatnosci.html'),
            'status'=>1
        ]);


        \App\Entities\HookContent::find($h4->id)->update(['slug'=>$h4->id.'-'.$h4->alias]);


    }
}
