<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TagRepository;
use App\Entities\Tag;
use App\Validators\TagValidator;

/**
 * Class TagRepositoryEloquent
 * @package namespace App\Repositories;
 */
class TagRepositoryEloquent extends BaseRepository implements TagRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Tag::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function searchByCriteria($data){

        $query = Tag::where(function($q) use ($data){

            if($data['frase']!='' || !is_null($data['frase'])) {

                $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');

            }



        });


        $elements = $query
            ->skip($data['start'])
            ->take($data['limit'])
            ->orderBy('name', 'ASC')
            ->get();


        $cquery = Tag::where(function($q) use ($data){

            if($data['frase']!='' || !is_null($data['frase'])) {

                $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');

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
