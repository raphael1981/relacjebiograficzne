<?php

namespace App\AdvancedSearch;

use App\Entities\Fragment;
use App\Entities\Record;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\Debugbar\Facade as Debugbar;
use App\Helpers\SearchHelp;
use Symfony\Component\Translation\Interval;
use Carbon\Carbon;

class AdvancedSearchRepository
{


    public function advancedSearchExecute($data){

        $intervals = null;

        if($data['mode']=='date') {

            $begin = $this->createDateByDataBegin($data['begin']);
            $end = $this->createDateByDataEnd($data['end']);


            if (is_null($begin) && !is_null($end)) {

                $intervals = DB::table('intervals')
                    ->whereDate('end', '<=', $end)->get();

            } elseif (!is_null($begin) && is_null($end)) {

                $intervals = DB::table('intervals')
                    ->whereDate('begin', '>=', $begin)->get();

            } elseif (!is_null($begin) && !is_null($end)) {

                $intervals = DB::table('intervals')
                    ->whereDate('end', '<=', $end)
                    ->whereDate('begin', '>=', $begin)->get();
            } else {

                $intervals = null;

            }

        }

        $select_array = ['fragments.id',
                            'fragments.record_id',
                            'records.id as rid',
                            'records.title',
                            'records.type',
                            'records.alias as ralias',
                            'fragments.start',
                            'fragments.content'
                        ];

        if(!is_null($intervals) && $data['mode']=='date') {

            $inter_array = [
                'intervals.id as iid',
                'intervals.name',
                'intervals.alias',
                'intervals.begin',
                'intervals.end',
                'intervalgables.interval_id',
                'intervalgables.intervalgables_id',
                'intervalgables.intervalgables_type'
            ];

            $select_array = array_merge($select_array, $inter_array);

        }


        if(!empty($data['places']) && $data['mode']=='place') {

            $plc_array = [
                'places.id as pid',
                'places.name',
                'placegables.place_id',
                'placegables.placegables_id',
                'placegables.placegables_type'
            ];

            $select_array = array_merge($select_array, $plc_array);

        }



        $sql = DB::table('records');
        $sql->select($select_array);



        $sql->join('fragments', 'records.id', '=', 'fragments.record_id');


        if(!is_null($intervals) && $data['mode']=='date'){


            $sql->join('intervalgables', 'fragments.id', '=', 'intervalgables.intervalgables_id')
                ->where('intervalgables.intervalgables_type', 'App\Entities\Fragment');

            $sql->join('intervals', 'intervals.id', '=', 'intervalgables.interval_id');


        }

        if(!is_null($intervals) && $data['mode']=='date') {


            $sql->where(function ($q) use ($intervals) {

                foreach ($intervals as $key => $intv) {
                    $q->orWhere('intervals.id', $intv->id);
                }

            });

        }



        if(!empty($data['places']) && $data['mode']=='place') {


            $sql->join('placegables', 'fragments.id', '=', 'placegables.placegables_id')
                ->where('placegables.placegables_type', 'App\Entities\Fragment');

            $sql->join('places', 'places.id', '=', 'placegables.place_id');

            $sql->where(function($q) use ($data){

                foreach ($data['places'] as $key => $plc) {

                    $q->orWhere('places.id', $plc);

                }

            });

        }




        if(!empty($data['places']) && $data['mode']=='place') {

            $sql->where(function($q) use ($data){

                foreach ($data['places'] as $key => $plc) {

                    $q->orWhere('places.id', $plc);

                }

            });

        }


//        return $sql->toSql();
        

        $result = $sql->get();


//        $filter_next = [];
//
//        if(!empty($data['places'])) {
//
//            if (count($data['places']) > 0) {
//
//                foreach ($result as $k => $f) {
//
//                    if (Fragment::find($f->id)->places()->where('places.id', $data['places'])->exists()) {
//                        array_push($filter_next, $f);
//                    }
//
//                }
//
//            }
//        }else{
//
//            $filter_next = $result;
//
//        }


        $array = [];

        foreach($result as $k=>$r){

            if(key_exists($r->rid, $array)){

                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid
                ];


                $array[$r->rid]['fcount']++;


            }else{


                $array[$r->rid] = [];
                $array[$r->rid]['record'] = [
                    'title'=>$r->title,
                    'rid'=>$r->rid,
                    'type'=>$r->type,
                    'alias'=>$r->ralias
                ];

                $array[$r->rid]['fcount'] = 1;

                $array[$r->rid]['fragments'] = [];
                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid
                ];

            }



        }

        if(empty($array))
            return response(0,200);

        $chunk = array_chunk($array, 4);

        return $chunk[0];


    }


    public function advancedSearchExecuteAll($data){

        $intervals = null;

        if($data['mode']=='date') {

            $begin = $this->createDateByDataBegin($data['begin']);
            $end = $this->createDateByDataEnd($data['end']);


            if (is_null($begin) && !is_null($end)) {

                $intervals = DB::table('intervals')
                    ->whereDate('end', '<=', $end)->get();

            } elseif (!is_null($begin) && is_null($end)) {

                $intervals = DB::table('intervals')
                    ->whereDate('begin', '>=', $begin)->get();

            } elseif (!is_null($begin) && !is_null($end)) {

                $intervals = DB::table('intervals')
                    ->whereDate('end', '<=', $end)
                    ->whereDate('begin', '>=', $begin)->get();
            } else {

                $intervals = null;

            }

        }

        $select_array = ['fragments.id',
            'fragments.record_id',
            'records.id as rid',
            'records.title',
            'records.type',
            'records.alias as ralias',
            'fragments.start',
            'fragments.content'
        ];

        if(!is_null($intervals) && $data['mode']=='date') {

            $inter_array = [
                'intervals.id as iid',
                'intervals.name',
                'intervals.alias',
                'intervals.begin',
                'intervals.end',
                'intervalgables.interval_id',
                'intervalgables.intervalgables_id',
                'intervalgables.intervalgables_type'
            ];

            $select_array = array_merge($select_array, $inter_array);

        }


        if(!empty($data['places']) && $data['mode']=='place') {

            $plc_array = [
                'places.id as pid',
                'places.name',
                'placegables.place_id',
                'placegables.placegables_id',
                'placegables.placegables_type'
            ];

            $select_array = array_merge($select_array, $plc_array);

        }



        $sql = DB::table('records');
        $sql->select($select_array);



        $sql->join('fragments', 'records.id', '=', 'fragments.record_id');


        if(!is_null($intervals) && $data['mode']=='date'){


            $sql->join('intervalgables', 'fragments.id', '=', 'intervalgables.intervalgables_id')
                ->where('intervalgables.intervalgables_type', 'App\Entities\Fragment');

            $sql->join('intervals', 'intervals.id', '=', 'intervalgables.interval_id');


        }

        if(!is_null($intervals) && $data['mode']=='date') {


            $sql->where(function ($q) use ($intervals) {

                foreach ($intervals as $key => $intv) {
                    $q->orWhere('intervals.id', $intv->id);
                }

            });

        }



        if(!empty($data['places']) && $data['mode']=='place') {


            $sql->join('placegables', 'fragments.id', '=', 'placegables.placegables_id')
                ->where('placegables.placegables_type', 'App\Entities\Fragment');

            $sql->join('places', 'places.id', '=', 'placegables.place_id');

            $sql->where(function($q) use ($data){

                foreach ($data['places'] as $key => $plc) {

                    $q->orWhere('places.id', $plc);

                }

            });

        }




        if(!empty($data['places']) && $data['mode']=='place') {

            $sql->where(function($q) use ($data){

                foreach ($data['places'] as $key => $plc) {

                    $q->orWhere('places.id', $plc);

                }

            });

        }


//        return $sql->toSql();


        $result = $sql->get();


//        $filter_next = [];
//
//        if(!empty($data['places'])) {
//
//            if (count($data['places']) > 0) {
//
//                foreach ($result as $k => $f) {
//
//                    if (Fragment::find($f->id)->places()->where('places.id', $data['places'])->exists()) {
//                        array_push($filter_next, $f);
//                    }
//
//                }
//
//            }
//        }else{
//
//            $filter_next = $result;
//
//        }


        $array = [];

        foreach($result as $k=>$r){

            if(key_exists($r->rid, $array)){

                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid
                ];


                $array[$r->rid]['fcount']++;


            }else{


                $array[$r->rid] = [];
                $array[$r->rid]['record'] = [
                    'title'=>$r->title,
                    'rid'=>$r->rid,
                    'type'=>$r->type,
                    'alias'=>$r->ralias
                ];

                $array[$r->rid]['fcount'] = 1;

                $array[$r->rid]['fragments'] = [];
                $array[$r->rid]['fragments'][] = [
                    'id'=>$r->id,
                    'start'=>$r->start,
                    'content'=>strip_tags($r->content),
                    'rid'=>$r->rid
                ];

            }



        }

        if(empty($array))
            return response(0,200);


        return $array;


    }



    private function createDateByDataBegin($d){

        if(is_null($d['day']) || $d['day']==''){
            $day = 1;
        }else{
            $day = $d['day'];
        }

        if(is_null($d['month']) || $d['month']==''){
            $month = 1;
        }else{
            $month = $d['month'];
        }

        if(is_null($d['year']) || $d['year']==''){
        }else{
            $year = $d['year'];
        }

        if(!is_null($d['year'])) {

            return Carbon::createFromDate($year, $month, $day)->toDateString();

        }

        return null;

    }


    private function createDateByDataEnd($d){

        if(is_null($d['day']) || $d['day']==''){
            $day = 31;
        }else{
            $day = $d['day'];
        }

        if(is_null($d['month']) || $d['month']==''){
            $month = 12;
        }else{
            $month = $d['month'];
        }

        if(is_null($d['year']) || $d['year']==''){
        }else{
            $year = $d['year'];
        }

        if(!is_null($d['year'])) {

            return Carbon::createFromDate($year, $month, $day)->toDateString();

        }

        return null;

    }



}