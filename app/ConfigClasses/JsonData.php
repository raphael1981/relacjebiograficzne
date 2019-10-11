<?php

namespace App\ConfigClasses;


use App\Entities\Category;

class JsonData{

    public function getOcupations(){

        $array = [
            'Informatyk',
            'Inne'
        ];

        return $array;

    }


    public function getCustomerStatuses(){

        $before = config('services')['stbeforever'];
        $after = config('services')['stafterver'];

        return array_merge($before,$after);

    }


    public function getArticleCategories(){

        return Category::all();

    }


    public function getRedactorsProfessions(){

        $array = [
            [
                'name'=>'fotograf',
                'id'=>'fotograf'
            ],
            [
                'name'=>'operator kamery',
                'id'=>'operator kamery'
            ],
            [
                'name'=>'redaktor',
                'id'=>'redaktor'
            ],
            [
                'name'=>'montażysta filmowy',
                'id'=>'montażysta filmowy'
            ],
        ];

        return response(\GuzzleHttp\json_encode($array), 200, ['Content-Type'=>'application/json']);

    }


    public function getRegisterTargets(){

        $array = [

            ['key'=>'artykuł naukowy','value'=>'artykuły naukowe'],
            ['key'=>'artykuł prasowy','value'=>'artykuł prasowy'],
            ['key'=>'film dokumentalny','value'=>'film dokumentalny'],
            ['key'=>'inny','value'=>'inny'],
            ['key'=>'praca edukacyjna','value'=>'praca edukacyjna'],
            ['key'=>'praca naukowa','value'=>'praca naukowa'],
            ['key'=>'projekt artystyczny','value'=>'projekt artystyczny'],
            ['key'=>'projekt szkolny','value'=>'projekt szkolny'],
            ['key'=>'prywatny','value'=>'poszukiwania genealogiczne'],
            ['key'=>'poszukiwania genealogiczne','value'=>'poszukiwania genealogiczne'],
            ['key'=>'wystawa','value'=>'wystawa']
        ];

        return $array;
    }


    public function getImagesFromPublicFolderToSwipe($folder){

        $arr = [
            ['img'=>'1.jpg', 'caption'=>'Karolina Żłobecka w czasie rozmowy z panią Ireną Sandecką, Krzemieniec, 2009. Fot. Dominik Czapigo'],
            ['img'=>'2.jpg', 'caption'=>'Maria Buko w czasie rozmowy z panią Aliną Bojarską, Warszawa, 2016. Fot. Iwona Makowska'],
            ['img'=>'3.jpg', 'caption'=>'Jarosław Pałka w czasie rozmowy z panem Stefanem Supranowiczem, Jawniuny k. Wilna, 2010. Fot. Dominik Czapigo'],
            ['img'=>'7.jpg', 'caption'=>'Katarzyna Madoń-Mitzner (po lewej). Fot. Piotr Filipkowski'],
            ['img'=>'9.jpg', 'caption'=>'Grzegorz Kaczorowski w czasie rozmowy z panem Zenonem Szyksznianem, Prucie k. Rymszan, 2011. Fot. Maciej Melon'],
            ['img'=>'6.jpg', 'caption'=>'Magdalena Stopa (po prawej).'],
            ['img'=>'4.jpg', 'caption'=>'Jarosław Pałka w czasie rozmowy z panią Rafaelą Wróblewską, Kołybajiwka k. Kamieńca Podolskiego, 2008, Fot. Dominik Czapigo'],
            ['img'=>'5.jpg', 'caption'=>'Piotr Filipkowski z panem Leopoldem Kałakajło, Czerniowce, 2009. Fot. Dominik Czapigo']
        ];

        $src = [];

        foreach($arr as $k=>$v){

            $std = new \stdClass();
            $std->img = '/'.$folder.'/'.$v['img'];
            $std->size = getimagesize(public_path().'/'.$folder.'/'.$v['img']);
            $std->caption = $v['caption'];
            array_push($src, $std);

        }

        return $src;


//        $images = glob(public_path().'/'.$folder.'/*', GLOB_BRACE);
//
//        $src = [];
//
//        foreach($images as $k=>$im){
//
//            $std = new \stdClass();
//            $std->img = url(str_replace(public_path(),'',$im));
//            $std->size = getimagesize($im);
//
//            array_push($src, $std);
//        }
//
//        return $src;

    }

}