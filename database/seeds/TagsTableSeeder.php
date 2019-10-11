<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = file_get_contents(url('json/tags.json'));
        $array = \GuzzleHttp\json_decode($tags);

        foreach($array  as $t) {

            \App\Entities\Tag::create([
                "name" => $t->name
            ]);

        }

    }
}
