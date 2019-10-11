<?php

namespace App\Repositories;

use App\Entities\Fragment;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RecordRepository;
use App\Entities\Record;
use App\Validators\RecordValidator;

/**
 * Class RecordRepositoryEloquent
 * @package namespace App\Repositories;
 */
class RecordRepositoryEloquent extends BaseRepository implements RecordRepository
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
        return Record::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function getLastRecords($start, $limit){

        $array = [];

        $records = Record::skip($start)
            ->take($limit)
            ->orderBy('published_at', 'desc')
            ->where('status',1)
            ->get();

        foreach($records as $k=>$rd){

            $array[$k] = new \stdClass();
            $array[$k]->rddata = $rd;
            $array[$k]->rdtime = $rd->duration;

        }

        return $array;

    }


    public function searchByCriteria($data){



        $query = Record::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['title']) {
                    $q->orWhere('title', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['signature']) {
                    $q->orWhere('signature', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['source']) {
                    $q->orWhere('source', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['xmltrans']) {
                    $q->orWhere('xmltrans', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['description']) {
                    $q->orWhere('description', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['summary']) {
                    $q->orWhere('summary', 'LIKE', '%' . $data['frase'] . '%');
                }

            }



        });

        if(isset($data['filter']['filetype'])){

            if($data['filter']['filetype']!='all'){
                $query->where('type',$data['filter']['filetype'] );
            }

        }


        $elements = $query
            ->skip($data['start'])
            ->take($data['limit'])
            ->get();


        $cquery = Record::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['title']) {
                    $q->orWhere('title', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['signature']) {
                    $q->orWhere('signature', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['source']) {
                    $q->orWhere('source', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['xmltrans']) {
                    $q->orWhere('xmltrans', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['description']) {
                    $q->orWhere('description', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['summary']) {
                    $q->orWhere('summary', 'LIKE', '%' . $data['frase'] . '%');
                }

            }


        });

        if(isset($data['filter']['filetype'])){

            if($data['filter']['filetype']!='all'){
                $cquery->where('type',$data['filter']['filetype'] );
            }

        }

        $total = $cquery->get();
        $count = count($total);

        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $count;

        return json_encode($std);

    }



    public function getFullRecordDataById($id){

        $std = new \stdClass();

        $std->record = $this->find($id);;
        $std->fragments = $std->record->fragments()->orderBy('ord')->get();
        $std->tags = $std->record->tags()->get();
        $std->intervals = $std->record->intervals()->get();

        foreach($std->fragments as $k=>$f){

            $std->fragments[$k]->tags = Fragment::find($f->id)->tags()->get();

        }

        foreach($std->fragments as $k=>$f){

            $std->fragments[$k]->intervals = Fragment::find($f->id)->intervals()->get();

        }

        foreach($std->fragments as $k=>$f){

            $std->fragments[$k]->places = Fragment::find($f->id)->places()->get();

        }


        return json_encode($std);

    }


    public function createNewRecordGetId($data){

        if(is_null($data['xmltrans'])){

            $xmlname = str_slug($data['title'],'-').'-'.time().'.xml';

            /*
            file_put_contents(base_path('public/xml').'/'.$xmlname,
                '<?xml version=\'1.0\'?>
                <article>
                <section>
                <time>0</time>Wklej tekst
                </section>
                </article>');
            */

            file_put_contents(config('services')['timesign_xmlpath'].'/'.$xmlname,
                '<?xml version=\'1.0\'?>
                <article>
                <section>
                <time>0</time>Wklej tekst
                </section>
                </article>');
        }else{
            $xmlname = $data['xmltrans']['filename'];
        }


        $model = $this->create([
            'title'=>$data['title'],
            'alias'=>str_slug($data['title'],'-'),
            'signature'=>$data['signature'],
            'source'=>$data['source']['filename'],
            'xmltrans'=>$xmlname,
            'description'=>$data['description'],
            'summary'=>$data['summary'],
            'duration'=>$data['duration'],
            'type'=>$data['type']
        ]);


        return $model->id;

    }


    public function updateSortString($id){

        $inter = Record::find($id)->interviewees()->first();

        if(count($inter)>0) {

            $this->update([
                'sort_string' => $this->makeNameTransform($inter->surname) . $this->makeNameTransform($inter->name)
            ], $id);

        }

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
