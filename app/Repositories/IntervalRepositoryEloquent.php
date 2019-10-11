<?php

namespace App\Repositories;

use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\IntervalRepository;
use App\Entities\Interval;
use App\Validators\IntervalValidator;

/**
 * Class IntervalRepositoryEloquent
 * @package namespace App\Repositories;
 */
class IntervalRepositoryEloquent extends BaseRepository implements IntervalRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Interval::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }



    public function searchByCriteria($data){


        if(!is_null($data['date']['start'])){
            $ex_start = explode("T",$data['date']['start']);
            $data['date']['start'] = $ex_start[0];
        }

        if(!is_null($data['date']['end'])){
            $ex_end = explode("T",$data['date']['end']);
            $data['date']['end'] = $ex_end[0];
        }


        $query = Interval::where(function($q) use ($data){

            if($data['frase']!='' || !is_null($data['frase'])) {

                $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                $q->orWhere('alias', 'LIKE', '%' . $data['frase'] . '%');

            }

        });

        if(is_null($data['date']['start'])){


        }elseif(!is_null($data['date']['start']) && is_null($data['date']['end'])){

            $query->where('begin', '>=', $data['date']['start']);

        }elseif(is_null($data['date']['start']) && !is_null($data['date']['end'])){

            $query->where('begin', '<=',$data['date']['end']);

        }elseif(!is_null($data['date']['end']) && !is_null($data['date']['start'])){

            $query->where('begin', '>=',$data['date']['start']);
            $query->where('begin', '<=',$data['date']['end']);

        }


        $elements = $query
            ->skip($data['start'])
            ->take($data['limit'])
            ->orderBy('begin', 'ASC')
            ->get();




        $cquery = Interval::where(function($qc) use ($data){

            if($data['frase']!='' || !is_null($data['frase'])) {

                $qc->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                $qc->orWhere('alias', 'LIKE', '%' . $data['frase'] . '%');

            }

        });

        if(is_null($data['date']['start'])){


        }elseif(!is_null($data['date']['start']) && is_null($data['date']['end'])){

            $cquery->where('begin', '>=', $data['date']['start']);

        }elseif(is_null($data['date']['start']) && !is_null($data['date']['end'])){

            $cquery->where('begin', '<=',$data['date']['end']);

        }elseif(!is_null($data['date']['end']) && !is_null($data['date']['start'])){

            $cquery->where('begin', '>=',$data['date']['start']);
            $cquery->where('end', '<=',$data['date']['end']);

        }


        $total = $cquery->get();
        $count = count($total);

        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $count;

        return json_encode($std);


    }

    public function checkIsInterval($data){

        $std = new \stdClass();
        $criteria = $data['data'];
        $std->begin = $this->createDateByDataBegin($criteria['begin']);
        $std->end = $this->createDateByDataEnd($criteria['end']);
        $std->name = $criteria['name'];
//        $std->filter = $data['filter'];




        $std->intervals = Interval::where(function($q) use ($std){

//            if($std->filter['name']){
//                $q->where('name', 'LIKE', '%'.$std->name.'%');
//            }
//
//            if($std->filter['begin']){
//                $q->where('begin', $std->begin);
//            }
//
//            if($std->filter['end']){
//                $q->where('end', $std->end);
//            }

            if($std->name!=''){
                $q->where('name', 'LIKE', '%'.$std->name.'%');
            }

            if(!is_null($std->begin)){
                $q->where('begin', $std->begin);
            }

            if(!is_null($std->end)){
                $q->where('end', $std->end);
            }



        })->get();


        return \GuzzleHttp\json_encode($std);

    }


    private function createDateByDataBegin($d){

        if(is_null($d['day'])){
            $day = 1;
        }else{
            $day = $d['day'];
        }

        if(is_null($d['month'])){
            $month = 1;
        }else{
            $month = $d['month'];
        }

        if(is_null($d['year'])){
        }else{
            $year = $d['year'];
        }

        if(!is_null($d['year'])) {

            return Carbon::createFromDate($year, $month, $day)->toDateString();

        }

        return null;

    }


    private function createDateByDataEnd($d){

        if(is_null($d['day'])){
            $day = 31;
        }else{
            $day = $d['day'];
        }

        if(is_null($d['month'])){
            $month = 12;
        }else{
            $month = $d['month'];
        }

        if(is_null($d['year'])){
        }else{
            $year = $d['year'];
        }

        if(!is_null($d['year'])) {

            return Carbon::createFromDate($year, $month, $day)->toDateString();

        }

        return null;

    }

    public function createIntervalFromForData($data){

        $std = new \stdClass();

        $criteria = $data['data'];
        $std->begin = $this->createDateByDataBegin($criteria['begin']);
        $std->end = $this->createDateByDataEnd($criteria['end']);

        $intv = $this->create([
            'name'=>$criteria['name'],
            'alias'=>str_slug($criteria['name']),
            'begin'=>$std->begin,
            'end'=>(!is_null($std->end))?$std->end:null
        ]);

        return $intv;
    }


}
