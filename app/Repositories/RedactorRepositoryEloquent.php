<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RedactorRepository;
use App\Entities\Redactor;
use App\Validators\RedactorValidator;

/**
 * Class RedactorRepositoryEloquent
 * @package namespace App\Repositories;
 */
class RedactorRepositoryEloquent extends BaseRepository implements RedactorRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Redactor::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }



    public function searchByCriteria($data){

        $query = Redactor::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['name']) {
                    $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['surname']) {
                    $q->orWhere('surname', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['email']) {
                    $q->orWhere('email', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['profession']) {
                    $q->orWhere('profession', 'LIKE', '%' . $data['frase'] . '%');
                }

            }



        });

        if(!is_null($data['filter']['status']['number'])){
            $query->where('status',$data['filter']['status']['number']);
        }


        $elements = $query
            ->skip($data['start'])
            ->take($data['limit'])
            ->get();


        $cquery = Redactor::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['name']) {
                    $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['surname']) {
                    $q->orWhere('surname', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['email']) {
                    $q->orWhere('email', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['profession']) {
                    $q->orWhere('profession', 'LIKE', '%' . $data['frase'] . '%');
                }

            }


        });

        if(!is_null($data['filter']['status']['number'])){
            $cquery->where('status',$data['filter']['status']['number']);
        }

        $total = $cquery->get();
        $count = count($total);

        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $count;

        return json_encode($std);

    }

}
