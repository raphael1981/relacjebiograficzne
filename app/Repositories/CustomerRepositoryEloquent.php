<?php

namespace App\Repositories;

use App\Entities\Region;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CustomerRepository;
use App\Entities\Customer;
use App\Validators\CustomerValidator;
use App\Events\CreateNewCustomer;

/**
 * Class CustomerRepositoryEloquent
 * @package namespace App\Repositories;
 */
class CustomerRepositoryEloquent extends BaseRepository implements CustomerRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Customer::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function newCustomerCreate($data){



        $data['verification_token'] = str_slug(bcrypt($data['email'].date('Ymdu')));
//        $data['ocupation'] = $data['ocupation']['name'];
//        $data['region_id'] = $data['woj']['id'];
        $data['password'] = bcrypt($data['password']);
        $data['status'] = -1;
        $data['institution_name'] = $data['institution'];
//        unset($data['woj']);


        $model = $this->model->newInstance($data);
        $model->save();

        event(new CreateNewCustomer($model));

        return $model;

    }


    public function searchByCriteria($data){



        $query = Customer::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['email']) {
                    $q->orWhere('email', 'LIKE', '%' . $data['frase'] . '%');
                }

            }


        });


        if(!is_null($data['filter']['status']['number'])){
            $query->where('status',$data['filter']['status']['number']);
        }

        if($data['filter']['region']['id']!=''){
            $query->where('region_id',$data['filter']['region']['id']);
        }

        if($data['filter']['ocupation']!='Wszystkie'){
            $query->where('ocupation',$data['filter']['ocupation']);
        }


        $elements = $query
                        ->skip($data['start'])
                        ->take($data['limit'])
                        ->get();



        $elements = $this->refactorSearchElements($elements);


        $cquery = Customer::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['email']) {
                    $q->orWhere('email', 'LIKE', '%' . $data['frase'] . '%');
                }

            }

        });

        if(!is_null($data['filter']['status']['number'])){
            $cquery->where('status',$data['filter']['status']['number']);
        }

        if($data['filter']['region']['id']!=''){
            $cquery->where('region_id',$data['filter']['region']['id']);
        }

        if($data['filter']['ocupation']!='Wszystkie'){
            $cquery->where('ocupation',$data['filter']['ocupation']);
        }


        $total = $cquery->get();

        $count = count($total);

        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $count;

        return json_encode($std);

    }

    private function refactorSearchElements($elements){

        $array = [];

        foreach($elements as $k=>$el){

            $array[$k] = new \stdClass();
            $array[$k] = $el;
            $array[$k]->region = Region::find($el->region_id);

        }

        return $array;

    }

}
