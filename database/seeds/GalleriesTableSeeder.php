<?php

use Illuminate\Database\Seeder;
//use CSD\Image\Image as ImageCSD;

class GalleriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(App\Entities\Gallery::class, 150)->create();
//
//        foreach(\App\Entities\Gallery::all() as $k=>$g){
//
//            $gm = \App\Entities\Gallery::find($g->id);
//            $gm->regexstamp = '%gallery:'.$g->id.'%';
//            $gm->save();
//
//            foreach(\App\Entities\Picture::all() as $key=>$p){
//                $add_or_not = (bool)random_int(0, 1);
//
//                if($add_or_not){
//                    $gm->pictures()->save($p);
//                }
//
//
//            }
//
//        }
//
//        foreach(\App\Entities\Article::all() as $k=>$a){
//
//            $add_or_not = (bool)random_int(0, 1);
//            $one_or_two = (bool)random_int(0, 1);
//
//            if($add_or_not){
//
//                $art = \App\Entities\Article::find($a->id);
//                    $gallery1 = \App\Entities\Gallery::find($k + 2);
//                    $gallery2 = \App\Entities\Gallery::find($k + 1);
//                    $art->galleries()->save($gallery1);
//                    $art->galleries()->save($gallery2);
//                if($one_or_two){
//
//                }else {
//                    $gallery = \App\Entities\Gallery::find($k + 1);
//                    $art->galleries()->save($gallery);
//                }
//
//            }else{
//
//            }
//
//        }

        foreach(\App\Entities\Interviewee::all() as $key=>$interviewee){

            if($this->getIptcImages($interviewee)){

                $g = \App\Entities\Gallery::create([
                        'name'=>$interviewee->name.' '.$interviewee->surname,
                        'alias'=>str_slug($interviewee->name.' '.$interviewee->surname),
                        'description'=>'',
                        'photos'=>'',
                        'regexstamp'=>'',
                        'destination'=>'gallery',
                        'mode'=>'iptcauthor',
                        'disk'=>'photos',
                        'status'=>0
                    ]);

                foreach($this->getIptcImages($interviewee) as $k=>$image){

                    $p = \App\Entities\Picture::create([
                        'source'=>$image,
                        'disk'=>'photos',
                        'description'=>''
                        ]);

                    $g->pictures()->save($p);

                }



            }

        }
        


        $cgarray = ['Kresy','Warszawa','Dzieciństwo','Zdjęcia uliczne', 'Wojsko', 'Wakacje', 'Europa', 'Wieś', 'Szkoła'];

        foreach($cgarray as $key=>$value){

            $g = \App\Entities\Gallery::create([
                'name'=>$value,
                'alias'=>str_slug($value),
                'description'=>'',
                'photos'=>'',
                'regexstamp'=>'',
                'destination'=>'gallery',
                'mode'=>'iptccategory',
                'disk'=>'photos',
                'status'=>1
            ]);

        }

    }


    private function getIptcImages($inter){

        $path = \Illuminate\Support\Facades\Storage::disk('photos')
            ->getDriver()
            ->getAdapter()
            ->getPathPrefix();

        $files = \Illuminate\Support\Facades\Storage::disk('photos')->files();

        if(count($files)>0){

            $array = [];

            foreach($files as $k=>$img){

                $imgobj = \CSD\Image\ImageCSD::fromFile($path . $img);
                $str_key_name = $inter->name.' '.$inter->surname;

                if($str_key_name==$imgobj->getAggregate()->getPhotographerName()){
                    $array[$k] = $img;
                }

            }

            return $array;

        }else{

            return false;

        }



    }


}
