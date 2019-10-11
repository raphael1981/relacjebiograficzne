<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserRepository;
use App\User;
use App\Validators\UserValidator;

/**
 * Class UserRepositoryEloquent
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function searchByCriteria($data){


        $query = User::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['name']) {
                    $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['name']) {
                    $q->orWhere('surname', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['name']) {
                    $q->orWhere('surname', 'LIKE', '%' . $data['frase'] . '%');
                }

            }

            if(!is_null($data['filter']['status']['number'])){
                $q->where('status',$data['filter']['status']['number']);
            }


        });

        if(!is_null($data['filter']['status']['number'])){

            $query->where('status',$data['filter']['status']['number']);

        }

        $elements = $query
                        ->skip($data['start'])
                        ->take($data['limit'])
                        ->get();

//        $elements = $this->refactorSearchElements($elements);

        $total = User::where(function($q) use ($data){


                if($data['frase']!='') {

                    if ($data['searchcolumns']['name']) {
                        $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                    }

                    if ($data['searchcolumns']['name']) {
                        $q->orWhere('surname', 'LIKE', '%' . $data['frase'] . '%');
                    }

                    if ($data['searchcolumns']['name']) {
                        $q->orWhere('surname', 'LIKE', '%' . $data['frase'] . '%');
                    }

                }

                if(!is_null($data['filter']['status']['number'])){
                    $q->where('status',$data['filter']['status']['number']);
                }

        })
            ->get();

        $count = count($total);

        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $count;

        return json_encode($std);

    }

    private function refactorSearchElements($elements){

//        $array = [];
//
//        foreach($elements as $k=>$el){
//
//            $array[$k] = new \stdClass();
//            $array[$k] = $el;
//            $array[$k]->category = Category::find($el->category_id);
//
//        }
//
//        return $array;

    }

}
