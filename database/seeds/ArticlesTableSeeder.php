<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        $art1 = \App\Entities\Article::create([
//
//            'category_id'=>1,
//            'title'=>'Album rodzinny: SPOJRZENIA. Fotografie z Archiwum Historii Mówionej Domu Spotkań z Historią i Ośrodka KARTA',
//            'alias'=>str_slug('Album rodzinny: SPOJRZENIA. Fotografie z Archiwum Historii Mówionej Domu Spotkań z Historią i Ośrodka KARTA','-'),
//            'intro_image'=>'13_ARCZYNSKI_STEFAN_021_male-683x501.jpg',
//            'disk'=>'pictures',
//            'intro'=>file_get_contents(public_path().'/news/news1-intro.html'),
//            'content'=>'',
//            'external_url'=>'http://dsh.waw.pl/wydarzenia/album-rodzinny-spojrzenia-fotografie-z-archiwum-historii-mowionej-domu-spotkan-z-historia-i-osrodka-karta-2/',
//            'target_type'=>'external',
//            'status'=>1,
//            'published_at'=>\Carbon\Carbon::now()
//
//        ]);
//
//
//        $news1images = json_decode(file_get_contents(public_path().'/seedimages//news1/news1.json'),true);
//
//        $gallery1 = \App\Entities\Gallery::create([
//            'name'=>'Album rodzinny',
//            'alias'=>str_slug('Album rodzinny: SPOJRZENIA. Fotografie z Archiwum Historii Mówionej Domu Spotkań z Historią i Ośrodka KARTA'),
//            'description'=>'',
//            'photos'=>'',
//            'regexstamp'=>'',
//            'destination'=>'article',
//            'mode'=>'database',
//            'disk'=>'pictures',
//            'status'=>1
//        ]);
//
//        foreach($news1images as $key=>$value){
//
//            $p = \App\Entities\Picture::create([
//
//                'source'=>$value,
//                'disk'=>'pictures',
//                'description'=>''
//
//            ]);
//
//            $gallery1->pictures()->save($p);
//
//        }
//
//
//        $art1->galleries()->save($gallery1);
//
//
//        $art2 = \App\Entities\Article::create([
//
//            'category_id'=>1,
//            'title'=>'Polub nasz profil na Facebooku',
//            'alias'=>str_slug('Polub nasz profil na facebooku.','-'),
//            'intro_image'=>'10362636_1429922887268808_8292636036752659230_n.jpg',
//            'disk'=>'pictures',
//            'intro'=>file_get_contents(public_path().'/news/news2-intro.html'),
//            'content'=>'',
//            'external_url'=>'https://www.facebook.com/archiwumhistoriimowionej',
//            'target_type'=>'external',
//            'status'=>1,
//            'published_at'=>\Carbon\Carbon::now()
//
//        ]);
//
//
//        $art3 = \App\Entities\Article::create([
//
//            'category_id'=>1,
//            'title'=>'Wykaz wszystkich relacji',
//            'alias'=>str_slug('Wykaz wszystkich relacji','-'),
//            'intro_image'=>'',
//            'disk'=>'pictures',
//            'intro'=>file_get_contents(public_path().'/news/news3-intro.html'),
//            'content'=>'',
//            'external_url'=>'http://www.audiohistoria.pl',
//            'target_type'=>'external',
//            'status'=>1,
//            'published_at'=>\Carbon\Carbon::now()
//
//        ]);
//
//
//        $art4 = \App\Entities\Article::create([
//
//            'category_id'=>1,
//            'title'=>'Wirtualny spacer po dawnej Warszawie',
//            'alias'=>str_slug('Wirtualny spacer po dawnej Warszawie','-'),
//            'intro_image'=>'8Plociennik_Jerzy_AHM_2790_596_p.jpg',
//            'disk'=>'pictures',
//            'intro'=>file_get_contents(public_path().'/news/news4-intro.html'),
//            'content'=>'',
//            'external_url'=>'http://www.warszawazapamietana.dsh.waw.pl/',
//            'target_type'=>'external',
//            'status'=>1,
//            'published_at'=>\Carbon\Carbon::now()
//
//        ]);
//
//
//        $art5 = \App\Entities\Article::create([
//
//            'category_id'=>1,
//            'title'=>'Historia mówiona - warsztaty',
//            'alias'=>str_slug('Warsztaty','-'),
//            'intro_image'=>'Katarzynska_Urszula_002_wspolcz.JPG',
//            'disk'=>'pictures',
//            'intro'=>file_get_contents(public_path().'/news/news5-intro.html'),
//            'content'=>file_get_contents(public_path().'/news/news5.html'),
//            'target_type'=>'site',
//            'status'=>1,
//            'published_at'=>\Carbon\Carbon::now()
//
//        ]);




        factory(App\Entities\Article::class, 500)->create();
    }
}
