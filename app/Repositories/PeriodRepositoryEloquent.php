<?php

namespace App\Repositories;

use App\Entities\Record;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PeriodRepository;
use App\Entities\Period;
use App\Validators\PeriodValidator;

/**
 * Class PeriodRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PeriodRepositoryEloquent extends BaseRepository implements PeriodRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Period::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }



    public function searchByCriteria($data){

        $query = Period::where(function($q) use ($data){

            if($data['frase']!='' || !is_null($data['frase'])) {

                $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                $q->orWhere('alias', 'LIKE', '%' . $data['frase'] . '%');

            }



        });


        $elements = $query
            ->skip($data['start'])
            ->take($data['limit'])
            ->get();


        $cquery = Period::where(function($q) use ($data){

            if($data['frase']!='' || !is_null($data['frase'])) {

                $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                $q->orWhere('alias', 'LIKE', '%' . $data['frase'] . '%');

            }



        });

        $total = $cquery->get();
        $count = count($total);

        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $count;

        return json_encode($std);

    }


    public function getFullPeriodData($id){

        $std = new \stdClass();

        $std->period = $this->find($id);
        $std->records = $std->period->records()->get();
        $std->rids = $this->getOnlyRecordsId($std->records);

        return \GuzzleHttp\json_encode($std);


    }


    private function getOnlyRecordsId($objs){

        $array = [];

        foreach($objs as $r){

            array_push($array, $r->id);

        }

        return $array;

    }




}
