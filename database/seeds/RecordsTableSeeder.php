<?php

use App\Entities\Interviewee;
use App\Entities\Record;
use Illuminate\Database\Seeder;
use App\Helpers\XMLHelper;

class RecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $biogram = '';


        /////////////////////////////////Eugeniusz Łatuchowski/////////////////////////////////////////////////////////

        $model = \App\Entities\Record::create(
                [
                    'title'=>'Eugeniusz Łatuchowski',
                    'alias'=>str_slug('Eugeniusz Łatuchowski'),
                    'signature'=>'AHM_3136',
                    'source'=>'Latuchowski_Eugeniusz_AHM_3136.mp3',
                    'xmltrans'=>'latuchowski.xml',
                    'description'=>'Ala ma kota',
                    'summary'=>'Ola ma psa',
                    'duration'=>45415,
                    'type'=>'audio',
                    'status'=>1,
                    'published_at'=>\Carbon\Carbon::now()
                ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/latuchowski.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }


        $rec = Record::find($id);
        $int = Interviewee::create([
                'name'=>'Eugeniusz',
                'surname'=>'Łatuchowski',
                'biography'=>file_get_contents(public_path().'/biography/Latuchowski_Eugeniusz_biogram.html'),
                'portrait'=>'Edward_Latuchowski2.jpg',
                'disk'=>'portraits',
                'status'=>1
            ]);

        $rec->interviewees()->save($int);


        /////////////////////////////////Eugeniusz Łatuchowski/////////////////////////////////////////////////////////

        /////////////////////////////////Tadeusz Bukowy////////////////////////////////////////////////////////////////


//        $model = \App\Entities\Record::create(
//            [
//                'title'=>'Tadeusz Bukowy',
//                'alias'=>str_slug('Tadeusz Bukowy'),
//                'signature'=>'AHM_V_0057',
//                'source'=>'bukowy_3000.mp4',
//                'xmltrans'=>'bukowy.xml',
//                'description'=>'Ala ma kota',
//                'summary'=>'Ola ma psa',
//                'type'=>'video',
//                'status'=>0,
//                'published_at'=>\Carbon\Carbon::now()
//            ]
//        );
//
//        $id = $model->id;
//
//        $xml = XMLHelper::readTranscriptionXML('/xml/bukowy.xml');
//
//        foreach($xml as $key=>$frg){
//
//            App\Entities\Fragment::create([
//                'record_id'=>$id,
//                'content'=>$frg['content'],
//                'start'=>$frg['time'],
//                'ord'=>$key
//            ]);
//
//        }
//
//
//        $rec = Record::find($id);
//        $int = Interviewee::create([
//            'name'=>'Tadeusz',
//            'surname'=>'Bukowy',
//            'biography'=>$biogram,
//            'portrait'=>'default.jpg',
//            'disk'=>'portraits',
//            'status'=>0
//        ]);
//
//        $rec->interviewees()->save($int);


        /////////////////////////////////Tadeusz Bukowy////////////////////////////////////////////////////////////////


        /////////////////////////////////Halina Cieszkowska////////////////////////////////////////////////////////////////


//        $model = \App\Entities\Record::create(
//            [
//                'title'=>'Halina Cieszkowska',
//                'alias'=>str_slug('Halina Cieszkowska'),
//                'signature'=>'AHM_V_0094',
//                'source'=>'cieszkowska.mp4',
//                'xmltrans'=>'cieszkowska.xml',
//                'description'=>'Ala ma kota',
//                'summary'=>'Ola ma psa',
//                'type'=>'video',
//                'status'=>0,
//                'published_at'=>\Carbon\Carbon::now()
//            ]
//        );
//
//        $id = $model->id;
//
//        $xml = XMLHelper::readTranscriptionXML('/xml/cieszkowska.xml');
//
//        foreach($xml as $key=>$frg){
//
//            App\Entities\Fragment::create([
//                'record_id'=>$id,
//                'content'=>$frg['content'],
//                'start'=>$frg['time'],
//                'ord'=>$key
//            ]);
//
//        }
//
//
//        $rec = Record::find($id);
//        $intCieszkowska = Interviewee::create([
//            'name'=>'Halina',
//            'surname'=>'Cieszkowska',
//            'biography'=>$biogram,
//            'portrait'=>'default.jpg',
//            'disk'=>'portraits',
//            'status'=>0
//        ]);
//
//        $rec->interviewees()->save($intCieszkowska);


        /////////////////////////////////Halina Cieszkowska////////////////////////////////////////////////////////////////


//        $model = \App\Entities\Record::create(
//            [
//                'title'=>'Halina Cieszkowska',
//                'alias'=>str_slug('Halina Cieszkowska'),
//                'signature'=>'AHM_V_0094',
//                'source'=>'cieszkowska_2.mp4',
//                'xmltrans'=>'cieszkowska_2.xml',
//                'description'=>'Ala ma kota',
//                'summary'=>'Ola ma psa',
//                'type'=>'video',
//                'status'=>0,
//                'published_at'=>\Carbon\Carbon::now()
//            ]
//        );
//
//        $id = $model->id;
//
//        $xml = XMLHelper::readTranscriptionXML('/xml/cieszkowska_2.xml');
//
//        foreach($xml as $key=>$frg){
//
//            App\Entities\Fragment::create([
//                'record_id'=>$id,
//                'content'=>$frg['content'],
//                'start'=>$frg['time'],
//                'ord'=>$key
//            ]);
//
//        }
//
//
//        $rec = Record::find($id);
//
//
//        $rec->interviewees()->save($intCieszkowska);


        /////////////////////////////////Halina Cieszkowska////////////////////////////////////////////////////////////////


        /////////////////////////////////Stella Czajkowska////////////////////////////////////////////////////////////////


//        $model = \App\Entities\Record::create(
//            [
//                'title'=>'Stella Czajkowska',
//                'alias'=>str_slug('Stella Czajkowska'),
//                'signature'=>'AHM_V_0087',
//                'source'=>'czajkowska.mp4',
//                'xmltrans'=>'czajkowska.xml',
//                'description'=>'Ala ma kota',
//                'summary'=>'Ola ma psa',
//                'type'=>'video',
//                'status'=>0,
//                'published_at'=>\Carbon\Carbon::now()
//            ]
//        );
//
//        $id = $model->id;
//
//        $xml = XMLHelper::readTranscriptionXML('/xml/czajkowska.xml');
//
//        foreach($xml as $key=>$frg){
//
//            App\Entities\Fragment::create([
//                'record_id'=>$id,
//                'content'=>$frg['content'],
//                'start'=>$frg['time'],
//                'ord'=>$key
//            ]);
//
//        }
//
//
//        $rec = Record::find($id);
//        $int = Interviewee::create([
//            'name'=>'Stella',
//            'surname'=>'Czajkowska',
//            'biography'=>$biogram,
//            'portrait'=>'default.jpg',
//            'disk'=>'portraits',
//            'status'=>0
//        ]);
//
//        $rec->interviewees()->save($int);



        /////////////////////////////////Stella Czajkowska////////////////////////////////////////////////////////////////

        /////////////////////////////////Zdzisław Stawicki////////////////////////////////////////////////////////////////


//        $model = \App\Entities\Record::create(
//            [
//                'title'=>'Zdzisław Stawicki',
//                'alias'=>str_slug('Zdzisław Stawicki'),
//                'signature'=>'AHM_V_0074',
//                'source'=>'Stawicki_new.mp4',
//                'xmltrans'=>'stawicki.xml',
//                'description'=>'Ala ma kota',
//                'summary'=>'Ola ma psa',
//                'type'=>'video',
//                'status'=>0,
//                'published_at'=>\Carbon\Carbon::now()
//            ]
//        );
//
//        $id = $model->id;
//
//        $xml = XMLHelper::readTranscriptionXML('/xml/stawicki.xml');
//
//        foreach($xml as $key=>$frg){
//
//            App\Entities\Fragment::create([
//                'record_id'=>$id,
//                'content'=>$frg['content'],
//                'start'=>$frg['time'],
//                'ord'=>$key
//            ]);
//
//        }
//
//
//        $rec = Record::find($id);
//        $int = Interviewee::create([
//            'name'=>'Zdzisław',
//            'surname'=>'Stawicki',
//            'biography'=>$biogram,
//            'portrait'=>'default.jpg',
//            'disk'=>'portraits',
//            'status'=>0
//        ]);
//
//        $rec->interviewees()->save($int);


        /////////////////////////////////Zdzisław Stawicki////////////////////////////////////////////////////////////////


        /////////////////////////////////Mahmud al Tayeb//////////////////////////////////////////////////////////////////


//        $model = \App\Entities\Record::create(
//            [
//                'title'=>'Mahmud al Tayeb',
//                'alias'=>str_slug('Mahmud al Tayeb'),
//                'signature'=>'AHM_V_0121',
//                'source'=>'tayeb_3000.mp4',
//                'xmltrans'=>'tayeb.xml',
//                'description'=>'Ala ma kota',
//                'summary'=>'Ola ma psa',
//                'type'=>'video',
//                'status'=>0,
//                'published_at'=>\Carbon\Carbon::now()
//            ]
//        );
//
//        $id = $model->id;
//
//        $xml = XMLHelper::readTranscriptionXML('/xml/tayeb.xml');
//
//        foreach($xml as $key=>$frg){
//
//            App\Entities\Fragment::create([
//                'record_id'=>$id,
//                'content'=>$frg['content'],
//                'start'=>$frg['time'],
//                'ord'=>$key
//            ]);
//
//        }
//
//
//        $rec = Record::find($id);
//        $int = Interviewee::create([
//            'name'=>'Mahmud',
//            'surname'=>'al Tayeb',
//            'biography'=>$biogram,
//            'portrait'=>'default.jpg',
//            'disk'=>'portraits',
//            'status'=>0
//        ]);
//
//        $rec->interviewees()->save($int);


        /////////////////////////////////Mahmud al Tayeb//////////////////////////////////////////////////////////////////

        /////////////////////////////////Maria Nowicka////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Maria Nowicka',
                'alias'=>str_slug('Maria Nowicka'),
                'signature'=>'AHM_V_0095',
                'source'=>'NOWICKA_MARIA_AHM_V_0095.mp4',
                'xmltrans'=>'nowicka.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>20219,
                'type'=>'video',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/nowicka.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Maria',
            'surname'=>'Nowicka',
            'biography'=>file_get_contents(public_path().'/biography/Maria_Nowicka_biogram.html'),
            'portrait'=>'Maria_Nowicka.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);

        /////////////////////////////////Maria Nowicka////////////////////////////////////////////////////////////////////

        /////////////////////////////////Helena Harbul////////////////////////////////////////////////////////////////////

        $model = \App\Entities\Record::create(
            [
                'title'=>'Helena Harbul',
                'alias'=>str_slug('Helena Harbul'),
                'signature'=>'AHM_2820',
                'source'=>'Harbul_Helena_AHM_2820.mp3',
                'xmltrans'=>'harbul.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>16799,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/harbul.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Helena',
            'surname'=>'Harbul',
            'biography'=>file_get_contents(public_path().'/biography/Harbul_Helena_biogram.html'),
            'portrait'=>'Harbul_Irena.JPG',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        /////////////////////////////////Helena Harbul////////////////////////////////////////////////////////////////////

        /////////////////////////////////Henryk Matulko////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Henryk Matulko',
                'alias'=>str_slug('Henryk Matulko'),
                'signature'=>'AHM_V_0070',
                'source'=>'MATULKO_HENRYK_AHM_V_0070_wersja_02.mp4',
                'xmltrans'=>'matulko.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>14943,
                'type'=>'video',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/matulko.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Henryk',
            'surname'=>'Matulko',
            'biography'=>$biogram,
            'portrait'=>'Henryk_Matulko.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        /////////////////////////////////Henryk Matulko////////////////////////////////////////////////////////////////////

        /////////////////////////////////Longin Glijer/////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Longin Glijer',
                'alias'=>str_slug('Longin Glijer'),
                'signature'=>'AHM_V_0084',
                'source'=>'GLIJER_LONGIN_AHM_V_0084.mp4',
                'xmltrans'=>'glijer.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>13332,
                'type'=>'video',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/glijer.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Longin',
            'surname'=>'Glijer',
            'biography'=>file_get_contents(public_path().'/biography/Longin_Glijer_biogram.html'),
            'portrait'=>'Longin_Glijer.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        /////////////////////////////////Longin Glijer/////////////////////////////////////////////////////////////////////

        /////////////////////////////////Wanda Hiszpańska/////////////////////////////////////////////////////////////////////


//        $model = \App\Entities\Record::create(
//            [
//                'title'=>'Wanda Hiszpańska',
//                'alias'=>str_slug('Wanda Hiszpańska'),
//                'signature'=>'AHM_327',
//                'source'=>'Hiszpanska_Wanda_AHM_327.mp3',
//                'xmltrans'=>'hiszpanska.xml',
//                'description'=>'Ala ma kota',
//                'summary'=>'Ola ma psa',
//                'type'=>'audio',
//                'status'=>1,
//                'published_at'=>\Carbon\Carbon::now()
//            ]
//        );
//
//        $id = $model->id;
//
//        $xml = XMLHelper::readTranscriptionXML('/xml/hiszpanska.xml');
//
//        foreach($xml as $key=>$frg){
//
//            App\Entities\Fragment::create([
//                'record_id'=>$id,
//                'content'=>$frg['content'],
//                'start'=>$frg['time'],
//                'ord'=>$key
//            ]);
//
//        }
//
//
//
//        $rec = Record::find($id);
//        $int = Interviewee::create([
//            'name'=>'Wanda',
//            'surname'=>'Hiszpańska',
//            'biography'=>file_get_contents(public_path().'/biography/Hiszpanska_Wanda_biogram.html'),
//            'portrait'=>'default.jpg',
//            'disk'=>'portraits',
//            'status'=>1
//        ]);
//
//        $rec->interviewees()->save($int);


        /////////////////////////////////Wanda Hiszpańska/////////////////////////////////////////////////////////////////////


        /////////////////////////////////Stanisław Janczak/////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Stanisław Janczak',
                'alias'=>str_slug('Stanisław Janczak'),
                'signature'=>'AHM_062',
                'source'=>'Janczak_Stanislaw_AHM_062.mp3',
                'xmltrans'=>'janczak.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>7060,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/janczak.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Stanisław',
            'surname'=>'Janczak',
            'biography'=>file_get_contents(public_path().'/biography/Janczak_Stanislaw_biogram.html'),
            'portrait'=>'Janczak_Stanislaw_wspolcz_04.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        /////////////////////////////////Stanisław Janczak/////////////////////////////////////////////////////////////////////


        /////////////////////////////////Alina Kałczyńska-Rodziewicz/////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Alina Kałczyńska-Rodziewicz',
                'alias'=>str_slug('Alina Kałczyńska-Rodziewicz'),
                'signature'=>'AHM_2640',
                'source'=>'Kalczynska_Rodziewicz_Alina_AHM_2640.mp3',
                'xmltrans'=>'kalczynska-rodziewicz.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>18933,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/kalczynska-rodziewicz.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Alina',
            'surname'=>'Kałczyńska-Rodziewicz',
            'biography'=>file_get_contents(public_path().'/biography/Kalczynska-Rodziewicz_Alina_biogram.html'),
            'portrait'=>'Kalczynska_Rodziewicz_Alina_049.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);




        /////////////////////////////////Alina Kałczyńska-Rodziewicz/////////////////////////////////////////////////////////////////////


        /////////////////////////////////Włodzimierz Kiecuń/////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Włodzimierz Kiecuń',
                'alias'=>str_slug('Włodzimierz Kiecuń'),
                'signature'=>'AHM_V_0092',
                'source'=>'KIECUN_WLODZIMIERZ_AHM_V_0092.mp4',
                'xmltrans'=>'kiecun.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>17600,
                'type'=>'video',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/kiecun.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Włodzimierz',
            'surname'=>'Kiecuń',
            'biography'=>file_get_contents(public_path().'/biography/Kiecun_Wlodzimierz_biogram.html'),
            'portrait'=>'Kiecun_Wlodzimierz_wspol_001.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        /////////////////////////////////Włodzimierz Kiecuń/////////////////////////////////////////////////////////////////////

        /////////////////////////////////Stanisław Kolanowski/////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Stanisław Kolanowski',
                'alias'=>str_slug('Stanisław Kolanowski'),
                'signature'=>'AHM_3347',
                'source'=>'Kolanowski_Stanislaw_AHM_3347.mp3',
                'xmltrans'=>'kolanowski.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>20015,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/kolanowski.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Stanisław',
            'surname'=>'Kolanowski',
            'biography'=>file_get_contents(public_path().'/biography/Kolanowski_Stanislaw.html'),
            'portrait'=>'default.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        /////////////////////////////////Stanisław Kolanowski/////////////////////////////////////////////////////////////////////


        /////////////////////////////////Krystyna Łyczywek////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Krystyna Łyczywek',
                'alias'=>str_slug('Krystyna Łyczywek'),
                'signature'=>'AHM_V_0116',
                'source'=>'LYCZYWEK_KRYSTYNA_AHM_V_0116.mp4',
                'xmltrans'=>'lyczywek.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>11711,
                'type'=>'video',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/lyczywek.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Krystyna',
            'surname'=>'Łyczywek',
            'biography'=>$biogram,
            'portrait'=>'Krytyna_Lyczywek.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);




        /////////////////////////////////Krystyna Łyczywek////////////////////////////////////////////////////////////////////////

        /////////////////////////////////Jan Makuch////////////////////////////////////////////////////////////////////////



        $model = \App\Entities\Record::create(
            [
                'title'=>'Jan Makuch',
                'alias'=>str_slug('Jan Makuch'),
                'signature'=>'AHM_609',
                'source'=>'Makuch_Jan_AHM_609.mp3',
                'xmltrans'=>'makuch.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>20637,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/makuch.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Jan',
            'surname'=>'Makuch',
            'biography'=>file_get_contents(public_path().'/biography/Makuch_Jan_biogram.html'),
            'portrait'=>'Makuch_Jan_wspolcz.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);




        /////////////////////////////////Jan Makuch////////////////////////////////////////////////////////////////////////

        /////////////////////////////////Józef Małek////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Józef Małek',
                'alias'=>str_slug('Józef Małek'),
                'signature'=>'AHM_3137',
                'source'=>'Malek_Jozef_AHM_3137.mp3',
                'xmltrans'=>'malek.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>24306,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/malek.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Józef',
            'surname'=>'Małek',
            'biography'=>file_get_contents(public_path().'/biography/Malek_Jozef_biogram.html'),
            'portrait'=>'Malek_Jozef_002.JPG',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        /////////////////////////////////Józef Małek////////////////////////////////////////////////////////////////////////


        /////////////////////////////////Irena Moderska////////////////////////////////////////////////////////////////////////



        $model = \App\Entities\Record::create(
            [
                'title'=>'Irena Moderska',
                'alias'=>str_slug('Irena Moderska'),
                'signature'=>'AHM_1326',
                'source'=>'Moderska_Irena_AHM_1326.mp3',
                'xmltrans'=>'moderska.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>18840,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/moderska.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Irena',
            'surname'=>'Moderska',
            'biography'=>file_get_contents(public_path().'/biography/Moderska_Irena_biogram.html'),
            'portrait'=>'Moderska_Irena_wspol_001.JPG',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);




        /////////////////////////////////Irena Moderska////////////////////////////////////////////////////////////////////////

        /////////////////////////////////Genowefa Olejniczak////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Genowefa Olejniczak',
                'alias'=>str_slug('Genowefa Olejniczak'),
                'signature'=>'AHM_V_0122',
                'source'=>'OLEJNICZAK_GENOWEFA_AHM_V_0122.mp4',
                'xmltrans'=>'olejniczak.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>8767,
                'type'=>'video',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/olejniczak.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Genowefa',
            'surname'=>'Olejniczak',
            'biography'=>$biogram,
            'portrait'=>'Genowefa_Olejniczak.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        /////////////////////////////////Genowefa Olejniczak////////////////////////////////////////////////////////////////////////


        /////////////////////////////////Jerzy Płóciennik////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Jerzy Płóciennik',
                'alias'=>str_slug('Jerzy Płóciennik'),
                'signature'=>'AHM_2790',
                'source'=>'Plociennik_Jerzy_AHM_2790.mp3',
                'xmltrans'=>'plociennik.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>14426,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/plociennik.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Jerzy',
            'surname'=>'Płóciennik',
            'biography'=>file_get_contents(public_path().'/biography/Plociennik_Jerzy_biogram.html'),
            'portrait'=>'default.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        /////////////////////////////////Jerzy Płóciennik ////////////////////////////////////////////////////////////////////////


        /////////////////////////////////Mieczysław Ptaśnik ////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Mieczysław Ptaśnik',
                'alias'=>str_slug('Mieczysław Ptaśnik'),
                'signature'=>'AHM_1752',
                'source'=>'Ptasnik_Mieczyslaw_AHM_1752.mp3',
                'xmltrans'=>'ptasnik.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>45697,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/ptasnik.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Mieczysław',
            'surname'=>'Ptaśnik',
            'biography'=>file_get_contents(public_path().'/biography/Ptasnik_Mieczyslaw_biogram.html'),
            'portrait'=>'Ptasnik_portret.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        /////////////////////////////////Mieczysław Ptaśnik ////////////////////////////////////////////////////////////////////////

        /////////////////////////////////Stanisław Sowiński ////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Stanisław Sowiński',
                'alias'=>str_slug('Stanisław Sowiński'),
                'signature'=>'AHM_2745',
                'source'=>'Sowinski_Stanislaw_AHM_2745.mp3',
                'xmltrans'=>'sowinski.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>33580,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/sowinski.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Stanisław',
            'surname'=>'Sowiński',
            'biography'=>file_get_contents(public_path().'/biography/Sowinski_Stanislaw_biogram.html'),
            'portrait'=>'Slowinski_Stanislaw (1).jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        /////////////////////////////////Stanisław Sowiński ////////////////////////////////////////////////////////////////////////

        /////////////////////////////////Wanda Traczyk-Stawska ////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Wanda Traczyk-Stawska',
                'alias'=>str_slug('Wanda Traczyk-Stawska'),
                'signature'=>'AHM_2686',
                'source'=>'Stawska_Wanda_AHM_2686.mp3',
                'xmltrans'=>'stawska.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>27673,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/stawska.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Wanda',
            'surname'=>'Traczyk-Stawska',
            'biography'=>file_get_contents(public_path().'/biography/Traczyk-Stawska_Wanda_biogram.html'),
            'portrait'=>'default.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        /////////////////////////////////Wanda Traczyk-Stawska ////////////////////////////////////////////////////////////////////////

        /////////////////////////////////Stanisław Swierczyński////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Stanisław Świerczyński',
                'alias'=>str_slug('Stanisław Świerczyński'),
                'signature'=>'AHM_916',
                'source'=>'Swierczynski_Stanislaw_AHM_916.mp3',
                'xmltrans'=>'swierczynski.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>15734,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/swierczynski.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Stanisław',
            'surname'=>'Świerczyński',
            'biography'=>$biogram,
            'portrait'=>'default.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);




        /////////////////////////////////Stanisław Swierczyński////////////////////////////////////////////////////////////////////////

        /////////////////////////////////Otton Tuszyński////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Otton Tuszyński',
                'alias'=>str_slug('Otton Tuszyński'),
                'signature'=>'AHM_1440',
                'source'=>'Tuszynski_Otton_AHM_1440.mp3',
                'xmltrans'=>'tuszynski.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>11580,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/tuszynski.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Otton',
            'surname'=>'Tuszyński',
            'biography'=>$biogram,
            'portrait'=>'Tuszynski_Otto.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        /////////////////////////////////Otton Tuszyński////////////////////////////////////////////////////////////////////////


        /////////////////////////////////Halina Aszkenazy-Engelhard/////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Halina Aszkenazy-Engelhard',
                'alias'=>str_slug('Halina Aszkenazy-Engelhard'),
                'signature'=>'AHM_2201',
                'source'=>'Aszkenazy_Engelhard_Halina_AHM_2201.mp3',
                'xmltrans'=>'aszkenazy-engelhard.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>9597,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/aszkenazy-engelhard.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Halina',
            'surname'=>'Aszkenazy-Engelhard',
            'biography'=>file_get_contents(public_path().'/biography/Aszkenazy-Engelhard_Halina_biogram.html'),
            'portrait'=>'Aszkenazy-Engelhard_Halina_002.JPG',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);




        /////////////////////////////////Halina Aszkenazy-Engelhard/////////////////////////////////////////////////////////////

        /////////////////////////////////Janusz Bąkowski////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Janusz Bąkowski',
                'alias'=>str_slug('Janusz Bąkowski'),
                'signature'=>'AHM_V_0001',
                'source'=>'BAKOWSKI_JANUSZ_AHM_V_0001.mp4',
                'xmltrans'=>'bakowski.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>14290,
                'type'=>'video',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/bakowski.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Janusz',
            'surname'=>'Bąkowski',
            'biography'=>'',
            'portrait'=>'Janusz_Bakowski.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);




        /////////////////////////////////Janusz Bąkowski////////////////////////////////////////////////////////////////////////

        ////////////////////////////////Juta Biela//////////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Juta Biela',
                'alias'=>str_slug('Juta Biela'),
                'signature'=>'AHM_V_0075',
                'source'=>'BIELA_JUTA_AHM_V_0075_poprawiona.mp4',
                'xmltrans'=>'biela.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>5117,
                'type'=>'video',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/biela.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Juta',
            'surname'=>'Biela',
            'biography'=>file_get_contents(public_path().'/biography/Biela_Juta_biogram.html'),
            'portrait'=>'Juta_Biela.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        ////////////////////////////////Juta Biela//////////////////////////////////////////////////////////////////////////////

        ///////////////////////////////Janusz Boniński//////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Janusz Boniński',
                'alias'=>str_slug('Janusz Boniński'),
                'signature'=>'AHM_2687',
                'source'=>'Boninski_Janusz_AHM_2687.mp3',
                'xmltrans'=>'boninski.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>36453,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/boninski.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Janusz',
            'surname'=>'Boniński',
            'biography'=>file_get_contents(public_path().'/biography/Boninski_Janusz_biogram.html'),
            'portrait'=>'Janusz_Boninski_AGapinska_16.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        ////////////////////////////////Janusz Boniński/////////////////////////////////////////////////////////////////////////

        ///////////////////////////////Wacława Kędzierska///////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Wacława Kędzierska',
                'alias'=>str_slug('Wacława Kędzierska'),
                'signature'=>'AHM_259',
                'source'=>'Kedzierska_Waclawa_AHM_259.mp3',
                'xmltrans'=>'kedzierska.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>18030,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/kedzierska.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Wacława',
            'surname'=>'Kędzierska',
            'biography'=>file_get_contents(public_path().'/biography/Kedzierska_Waclawa_biogram.html'),
            'portrait'=>'Kedzierska_Waclawa_wspolcz.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        ///////////////////////////////Wacława Kędzierska///////////////////////////////////////////////////////////////////////

        ///////////////////////////////Izaak Kornblum///////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Izaak Kornblum',
                'alias'=>str_slug('Izaak Kornblum'),
                'signature'=>'AHM_2296',
                'source'=>'Kornblum_Izaak_Waclaw_AHM_2296.mp3',
                'xmltrans'=>'kornblum.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>32739,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/kornblum.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Izaak',
            'surname'=>'Kornblum',
            'biography'=>file_get_contents(public_path().'/biography/Kornblum_Izaak_Waclaw_biogram.html'),
            'portrait'=>'Kornblum_IW_AHM_2296_020.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        ///////////////////////////////Izaak Kornblum///////////////////////////////////////////////////////////////////////////

        ///////////////////////////////Lucia Kowalska///////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Lucia Kowalska',
                'alias'=>str_slug('Lucia Kowalska'),
                'signature'=>'AHM_V_0063',
                'source'=>'KOWALSKA_LUCIA_AHM_V_0063_poprawiona.mp4',
                'xmltrans'=>'kowalska.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>4930,
                'type'=>'video',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/kowalska.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Lucia',
            'surname'=>'Kowalska',
            'biography'=>$biogram,
            'portrait'=>'Lucia_Kowalska.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        ///////////////////////////////Lucia Kowalska///////////////////////////////////////////////////////////////////////////


        //////////////////////////////Józef Rosołowski//////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Józef Rosołowski',
                'alias'=>str_slug('Józef Rosołowski'),
                'signature'=>'AHM_V_0086',
                'source'=>'ROSOLOWSKI_JOZEF_AHM_V_0086.mp4',
                'xmltrans'=>'rosolowski.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>15124,
                'type'=>'video',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/rosolowski.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Józef',
            'surname'=>'Rosołowski',
            'biography'=>$biogram,
            'portrait'=>'Jozef_Rosolowski.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);



        //////////////////////////////Józef Rosołowski//////////////////////////////////////////////////////////////////////////


        //////////////////////////////Maria Rudzka-Kantorowicz//////////////////////////////////////////////////////////////////

        $model = \App\Entities\Record::create(
            [
                'title'=>'Maria Rudzka-Kantorowicz',
                'alias'=>str_slug('Maria Rudzka-Kantorowicz'),
                'signature'=>'AHM_655',
                'source'=>'Rudzka_Kantorowicz_Maria_AHM_655.mp3',
                'xmltrans'=>'rudzka-kantorowicz.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>44302,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/rudzka-kantorowicz.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Maria',
            'surname'=>'Rudzka-Kantorowicz',
            'biography'=>$biogram,
            'portrait'=>'default.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        //////////////////////////////Maria Rudzka-Kantorowicz//////////////////////////////////////////////////////////////////

        /////////////////////////////Jerzy Zoller///////////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Jerzy Zoller',
                'alias'=>str_slug('Jerzy Zoller'),
                'signature'=>'AHM_1277',
                'source'=>'Zoller_Jerzy_AHM_1277.mp3',
                'xmltrans'=>'zoller.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>24820,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/zoller.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Jerzy',
            'surname'=>'Zoller',
            'biography'=>file_get_contents(public_path().'/biography/Zoller_Jerzy_biogram.html'),
            'portrait'=>'Zoller_Jerzy_012.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        /////////////////////////////Jerzy Zoller///////////////////////////////////////////////////////////////////////////////


        ////////////////////////////Juliusz Zychowicz//////////////////////////////////////////////////////////////////////////


        $model = \App\Entities\Record::create(
            [
                'title'=>'Juliusz Zychowicz',
                'alias'=>str_slug('Juliusz Zychowicz'),
                'signature'=>'AHM_171',
                'source'=>'Zychowicz_Juliusz_AHM_171.mp3',
                'xmltrans'=>'zychowicz.xml',
                'description'=>'Ala ma kota',
                'summary'=>'Ola ma psa',
                'duration'=>14236,
                'type'=>'audio',
                'status'=>1,
                'published_at'=>\Carbon\Carbon::now()
            ]
        );

        $id = $model->id;

        $xml = XMLHelper::readTranscriptionXML('/xml/zychowicz.xml');

        foreach($xml as $key=>$frg){

            App\Entities\Fragment::create([
                'record_id'=>$id,
                'content'=>$frg['content'],
                'start'=>$frg['time'],
                'ord'=>$key
            ]);

        }



        $rec = Record::find($id);
        $int = Interviewee::create([
            'name'=>'Juliusz',
            'surname'=>'Zychowicz',
            'biography'=>file_get_contents(public_path().'/biography/Zychowicz_Juliusz_biogram.html'),
            'portrait'=>'default.jpg',
            'disk'=>'portraits',
            'status'=>1
        ]);

        $rec->interviewees()->save($int);


        ////////////////////////////Juliusz Zychowicz//////////////////////////////////////////////////////////////////////////



//        factory(App\Entities\Record::class, 600)->create()->each(function($r) {
//
//            $intr = factory(App\Entities\Interviewee::class)->create();
//
//            $r->interviewees()->save($intr);
//
//            foreach(range(1,25) as $index){
//
//                $fr = factory(App\Entities\Fragment::class)->create([
//                    'record_id'=>$r->id,
//                    'start'=>($index==1)?0:$index*50,
//                    'ord'=>$index
//                ]);
//
//                $r->fragments()->save($fr);
//                $fr->interviewees()->save($intr);
//            }
//
//
//        });

		
		
    }
}
