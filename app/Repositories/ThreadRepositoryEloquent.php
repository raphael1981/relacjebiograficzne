<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ThreadRepository;
use App\Entities\Thread;
use App\Validators\ThreadValidator;

/**
 * Class ThreadRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ThreadRepositoryEloquent extends BaseRepository implements ThreadRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Thread::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function searchByCriteria($data){

        $query = Thread::where(function($q) use ($data){

            if($data['frase']!='' || !is_null($data['frase'])) {

                $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                $q->orWhere('alias', 'LIKE', '%' . $data['frase'] . '%');

            }



        });


        $elements = $query
            ->skip($data['start'])
            ->take($data['limit'])
            ->orderBy('created_at', 'DESC')
            ->get();


        $cquery = Thread::where(function($q) use ($data){

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


    public function getFullThreadData($id){

        $std = new \stdClass();

        $std->thread = $this->find($id);
        $std->records = $std->thread->records()->get();
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


    public function getThredsAndRecordsCount(){

        $ths = [];

        $threads = $this->all();

        foreach($threads as $key=>$value){

            array_push($ths, [
                'thread'=>$value,
                'rvideo'=>$this->find($value->id)->records()->where('type', 'video')->count(),
                'raudio'=>$this->find($value->id)->records()->where('type', 'audio')->count()
            ]);

        }


        return $ths;

    }


}
