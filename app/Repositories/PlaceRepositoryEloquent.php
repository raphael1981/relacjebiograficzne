<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PlaceRepository;
use App\Entities\Place;
use App\Validators\PlaceValidator;

/**
 * Class PlaceRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PlaceRepositoryEloquent extends BaseRepository implements PlaceRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Place::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function searchByCriteria($data){

        $query = Place::where(function($q) use ($data){

            if($data['frase']!='' || !is_null($data['frase'])) {

                $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                $q->orWhere('alias', 'LIKE', '%' . $data['frase'] . '%');

            }



        });


        $elements = $query
            ->skip($data['start'])
            ->take($data['limit'])
            ->orderBy('name', 'ASC')
            ->get();


        $cquery = Place::where(function($q) use ($data){

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

}
