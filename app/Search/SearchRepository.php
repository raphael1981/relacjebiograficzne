<?php

namespace App\Search;

use App\Entities\Fragment;
use App\Entities\Record;
use App\Entities\Suggestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Search\AsyncTreadCacheMake;
use Illuminate\Support\Facades\Log;
use Barryvdh\Debugbar\Facade as Debugbar;
use App\Helpers\SearchHelp;

class SearchRepository
{

//    private $cauth;
//    private $aauth;

    public function __construct()
    {
//        $this->cauth = Auth::guard('customer');
//        $this->aauth = Auth::guard();

    }





    public function findRecords($frase)
    {

        $cacheKey = $frase;
        $frase = SearchHelp::makeRegexSearch($frase);


//        if(Cache::has($frase)){
//            $array = Cache::get($frase);
//            return $array[0];
//        }



        $sql = DB::table('records')
            ->select(
                'fragments.id',
                'fragments.record_id',
                'records.id as rid',
                'records.title',
                'records.type',
                'records.alias',
                'fragments.start',
                'fragments.content'
//                DB::raw('count(`fragments`.`record_id`) as howmuch')
//                DB::raw('round(
//                    (
//                        length(`fragments`.`content`) -
//                        length(replace(`fragments`.`content`, "'.$frase.'", ""))
//                    )
//                    /
//                    length("'.$frase.'")
//                    ) as weight'),
//                DB::raw('replace(`fragments`.`content`, "'.$frase.'", "<b><i>'.$frase.'</i></b>") as signcontent')
            )
            ->join('fragments', 'records.id', '=', 'fragments.record_id')
            ->where('fragments.content', 'REGEXP', ''.$frase)
            ->orderByRaw("fragments.content COLLATE utf8_bin ASC")
            ->groupBy('records.id','fragments.id');
//            ->orderBy('howmuch', 'desc');


        $data = $sql->where('status',1)->get();


        $array = [];

        foreach($data as $k=>$r){

            if(key_exists($r->rid, $array)){

                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    //'signcontent'=>$r->signcontent,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid
//                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
                ];


                $array[$r->rid]['fcount']++;


            }else{


                $array[$r->rid] = [];
                $array[$r->rid]['record'] = [
                    'title'=>$r->title,
                    'rid'=>$r->rid,
                    'type'=>$r->type,
                    'alias'=>$r->alias
//                    'interviewee'=>Record::find($r->rid)->interviewees()->get()
                ];

                $array[$r->rid]['fcount'] = 1;

                $array[$r->rid]['fragments'] = [];
                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid
//                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
                ];

            }





        }


        if(empty($array))
            return response(0,200);

        $chunk = array_chunk($array, 4);

        return $chunk[0];
    }


    public function findRecordsPerfect($frase){





        $sql = DB::table('records')
            ->select(
                'fragments.id',
                'records.id as rid',
                'records.title',
                'records.type',
                'records.alias',
                'fragments.start',
                'fragments.content',
                DB::raw('round(
                    (
                        length(`fragments`.`content`) -
                        length(replace(`fragments`.`content`, "'.$frase.'", ""))
                    )
                    /
                    length("'.$frase.'")
                    ) as weight')
            )
            ->join('fragments', 'records.id', '=', 'fragments.record_id')
            ->where('fragments.content', 'RLIKE', ''.$frase.'')
//            ->where("CONVERT(fragments.content USING utf8) LIKE '%".$frase."%'")
            ->groupBy('records.id','fragments.id','fragments.content')
            ->orderByRaw("fragments.content COLLATE utf8_bin ASC")
            ->orderBy('weight', 'desc');



        $data = $sql->where('status',1)->get();

        $array = [];

        foreach($data as $k=>$r){

            if(key_exists($r->rid, $array)){

                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid,
                    'weight'=>$r->weight
//                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
                ];

            }else{
                $array[$r->rid] = [];
                $array[$r->rid]['record'] = [
                    'title'=>$r->title,
                    'rid'=>$r->rid,
                    'type'=>$r->type,
                    'alias'=>$r->alias
//                    'interviewee'=>Record::find($r->rid)->interviewees()->get()
                ];

                $array[$r->rid]['fragments'] = [];
                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid,
                    'weight'=>$r->weight
//                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
                ];

            }

        }

        if(empty($array))
            return response(0,200);

        $all = $array;

        $array = array_chunk($array,4);

        return array_values($all);

    }


//    public function findRecordsCache($frase){
//
//
//        $sql = DB::table('records')
//            ->select(
//                'fragments.id',
//                'records.id as rid',
//                'records.title',
//                'records.type',
//                'records.alias',
//                'fragments.start',
//                'fragments.content',
//                DB::raw('round(
//                    (
//                        length(`fragments`.`content`) -
//                        length(replace(`fragments`.`content`, "'.$frase.'", ""))
//                    )
//                    /
//                    length("'.$frase.'")
//                    ) as weight'),
//                DB::raw('replace(`fragments`.`content`, "'.$frase.'", "<b><i>'.$frase.'</i></b>") as signcontent')
//            )
//            ->join('fragments', 'records.id', '=', 'fragments.record_id')
//            ->where('fragments.content', 'RLIKE', ''.$frase)
//            ->groupBy('records.id','fragments.id','fragments.content')
//            ->orderBy('weight', 'desc');
//
//
//        $data = $sql->get();
//
//        $array = [];
//
//        foreach($data as $k=>$r){
//
//            if(key_exists($r->rid, $array)){
//
//                $array[$r->rid]['fragments'][] = [
//                    'id'=>$r->id,
//                    'start'=>$r->start,
//                    'signcontent'=>$r->signcontent,
////                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
//                ];
//
//            }else{
//                $array[$r->rid] = [];
//                $array[$r->rid]['record'] = [
//                    'title'=>$r->title,
//                    'rid'=>$r->rid,
//                    'type'=>$r->type,
//                    'alias'=>$r->alias
////                    'interviewee'=>Record::find($r->rid)->interviewees()->get()
//                ];
//
//                $array[$r->rid]['fragments'] = [];
//                $array[$r->rid]['fragments'][] = [
//                    'id'=>$r->id,
//                    'start'=>$r->start,
//                    'signcontent'=>$r->signcontent,
////                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
//                ];
//
//            }
//
//        }
//
//        if(empty($array))
//            return response(0,200);
//
//        $array = array_chunk($array,4);
//
//        Cache::forever($frase, $array);
//
//        return $array;
//
//    }


    public function findRecordsCache($frase)
    {


        $cacheKey = $frase;
        $frase = SearchHelp::makeRegexSearch($frase);



        $sql = DB::table('records')
            ->select(
                'fragments.id',
                'fragments.record_id',
                'records.id as rid',
                'records.title',
                'records.type',
                'records.alias',
                'fragments.start',
                'fragments.content'
//                DB::raw('count(`fragments`.`record_id`) as howmuch')
//                DB::raw('round(
//                    (
//                        length(`fragments`.`content`) -
//                        length(replace(`fragments`.`content`, "'.$frase.'", ""))
//                    )
//                    /
//                    length("'.$frase.'")
//                    ) as weight'),
//                DB::raw('replace(`fragments`.`content`, "'.$frase.'", "<b><i>'.$frase.'</i></b>") as signcontent')
            )
            ->join('fragments', 'records.id', '=', 'fragments.record_id')
            ->where('fragments.content', 'REGEXP', ''.$frase)
            ->orderByRaw("fragments.content COLLATE utf8_bin ASC")
            ->groupBy('records.id','fragments.id');
//            ->orderBy('howmuch', 'desc');


        $data = $sql->where('status',1)->get();


        $array = [];

        foreach($data as $k=>$r){

            if(key_exists($r->rid, $array)){

                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    //'signcontent'=>$r->signcontent,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid
//                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
                ];


                $array[$r->rid]['fcount']++;


            }else{


                $array[$r->rid] = [];
                $array[$r->rid]['record'] = [
                    'title'=>$r->title,
                    'rid'=>$r->rid,
                    'type'=>$r->type,
                    'alias'=>$r->alias
//                    'interviewee'=>Record::find($r->rid)->interviewees()->get()
                ];

                $array[$r->rid]['fcount'] = 1;

                $array[$r->rid]['fragments'] = [];
                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid
//                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
                ];

            }



        }


        if(empty($array))
            return response(0,200);

        $chunk = array_chunk($array, 4);

        Cache::forever($cacheKey, $chunk);
        Cache::forever($frase, $chunk);

        return $frase;
    }


    public function findRecordsPerfectCache($frase){

        $sql = DB::table('records')
            ->select(
                'fragments.id',
                'records.id as rid',
                'records.title',
                'records.type',
                'records.alias',
                'fragments.start',
                'fragments.content',
                DB::raw('round(
                    (
                        length(`fragments`.`content`) -
                        length(replace(`fragments`.`content`, "'.$frase.'", ""))
                    )
                    /
                    length("'.$frase.'")
                    ) as weight')
            )
            ->join('fragments', 'records.id', '=', 'fragments.record_id')
            ->where('fragments.content', 'RLIKE', ''.$frase.'')
            ->groupBy('records.id','fragments.id','fragments.content')
            ->orderByRaw("fragments.content COLLATE utf8_bin ASC")
            ->orderBy('weight', 'desc');


        $data = $sql->where('status',1)->get();

        $array = [];

        foreach($data as $k=>$r){

            if(key_exists($r->rid, $array)){

                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid,
                    'weight'=>$r->weight
//                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
                ];

            }else{
                $array[$r->rid] = [];
                $array[$r->rid]['record'] = [
                    'title'=>$r->title,
                    'rid'=>$r->rid,
                    'type'=>$r->type,
                    'alias'=>$r->alias
//                    'interviewee'=>Record::find($r->rid)->interviewees()->get()
                ];

                $array[$r->rid]['fragments'] = [];
                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid,
                    'weight'=>$r->weight
//                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
                ];

            }

        }

        if(empty($array))
            return response(0,200);



        $array = array_chunk($array,4);

        Cache::forever('perfect:'.$frase, $array);

        return $array;

    }

}