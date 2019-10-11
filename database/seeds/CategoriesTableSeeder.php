<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $title = 'Aktualności';

        \App\Entities\Category::create(
            [
                'ref'=>0,
                'name'=>$title,
                'alias'=>str_slug($title,'-'),
                'description'=>'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. ',
                'status'=>1
            ]
        );
    }
}
