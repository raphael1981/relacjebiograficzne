<?php

namespace App\Repositories;

use App\Entities\Record;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\IntervieweeRepository;
use App\Entities\Interviewee;
use App\Validators\IntervieweeValidator;

/**
 * Class IntervieweeRepositoryEloquent
 * @package namespace App\Repositories;
 */
class IntervieweeRepositoryEloquent extends BaseRepository implements IntervieweeRepository
{

    private $letters = ['ą','ę','ć','ś','ł','ń','ź','ż'];
    private $letters_to_replace = ['a','e','c','s','l','n','z','z'];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Interviewee::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function searchByCriteria($data){


        $query = Interviewee::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['name']) {
                    $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['surname']) {
                    $q->orWhere('surname', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['biography']) {
                    $q->orWhere('biography', 'LIKE', '%' . $data['frase'] . '%');
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

//        $elements = $this->refactorSearchElements($elements);

        $cquery = Interviewee::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['name']) {
                    $q->orWhere('name', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['surname']) {
                    $q->orWhere('surname', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['biography']) {
                    $q->orWhere('biography', 'LIKE', '%' . $data['frase'] . '%');
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

    public function getIntervieweesByIndex($data){


        $array = [];

        $ins = Interviewee::where(function($q) use ($data){

            foreach($data['index_data'] as $key=>$letter){
                $q->orWhere('surname', 'LIKE', $letter.'%');
            }


        })
            ->where('status',1)
            ->orderByRaw("surname COLLATE utf8_bin ASC")
            ->orderByRaw("name COLLATE utf8_bin ASC")
            ->get();

        foreach($ins as $k=>$in){
            $array[$k] = $in;
            $array[$k]->first_record = $this->find($in->id)->records()->where('status',1)->first();
        }


        return $array;
    }


    public function getAllInterviewees(){

        $array = [];

        //$ins = $this->orderBy('surname')->all();


        $ins = Interviewee::where('status',1)
//            ->orderByRaw("surname COLLATE utf8_bin ASC")
//            ->orderByRaw("name COLLATE utf8_bin ASC")
            ->orderByRaw('sort_string COLLATE utf8_bin ASC')
//            ->orderBy('sort_string')
            ->get();



        foreach($ins as $k=>$in){
            $array[$k] = $in;
            $array[$k]->first_record = $this->find($in->id)->records()->where('status',1)->first();
        }

        return $array;

    }
	
	
	public function getAll(){
	  return Interviewee::all();	
	}


    public function createIntervieweeGetId($data){

        $model = $this->create([
            'name'=>$data['name'],
            'surname'=>$data['surname'],
            'biography'=>$data['biography'],
            'portrait'=>(!is_null($data['portrait']))?$data['portrait']['fname']:'default.jpg',
            'disk'=>(!is_null($data['portrait']))?$data['disk']:'portraits'
        ]);

        return $model;

    }


    public function updateIntervieweeData($data){

        $this->update([
            'name'=>$data['name'],
            'surname'=>$data['surname'],
            'biography'=>$data['biography'],
            'portrait'=>(!is_null($data['portrait']))?$data['portrait']['fname']:'default.jpg',
            'disk'=>(!is_null($data['portrait']))?$data['disk']:'portraits'
        ], $data['id']);

    }


    public function updateSortString($id){

        $inter = Interviewee::find($id);

        $this->update([
            'sort_string'=> $this->makeNameTransform($inter->surname).$this->makeNameTransform($inter->name)
        ],$id);

    }

    private function makeNameTransform($last_name){

        $transform = '';

        $arr = $seed = preg_split('//u', mb_strtolower($last_name), -1, PREG_SPLIT_NO_EMPTY);

        foreach($arr as $wl){

            foreach($this->letters as $k=>$l){

                if($wl==$l){
                    $wl = $this->letters_to_replace[$k].'zz';
                }

            }

            $transform .= $wl;

        }

        return $transform;

    }

}
