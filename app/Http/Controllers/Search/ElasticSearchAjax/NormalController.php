<?php

namespace App\Http\Controllers\Search\ElasticSearchAjax;

use App\Entities\Place;
use App\Entities\Record;
use App\Entities\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Elasticsearch\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;
use App\Entities\Interval;
use App\Helpers\CustomHelp;

class NormalController extends Controller
{
    private $elasticsearch;
    private $curl;

    public function __construct(Client $elasticsearch, Curl $curl)
    {
        $this->elasticsearch = $elasticsearch;
        $this->curl = $curl;
    }


    private function checkIsFraseExept($phrase){

        if($phrase=="ojciec" || $phrase=="ojca" || $phrase=="ojcu") {

            $std = new \stdClass();

            $std->is_except = true;
            $std->words = [
//                "o"
            ];

        }else{

            $std = new \stdClass();

            $std->is_except = false;
            $std->words = [];

        }

        return $std;

    }


    public function searchByCriteria(Request $request){

        $exept_info = $this->checkIsFraseExept($request->input('phrase'));

        if($exept_info->is_except){
            $exept = $exept_info->words;
        }else{
            $exept = [];
        }

        $std = new \stdClass();

        $phrase = $request->input('phrase');

        $array_search = [
            "index" => "records",
            "type" => "elements",
            "body" => [
                "from" => $request->get('pag')['from'],
                "size" => $request->get('pag')['size'],
                "query" => [
                    "bool" => [
                        "must"=>[
                            ["match_phrase_prefix" => [
                                "fragments.except_content" => $request->get('phrase')
//                                "fragments.except_content" =>[
//                                    [
//                                    "query" => $request->get('phrase'),
//                                    "analyzer" => 'morfologik'
//                                    ]
//                                ]
                            ]]
                        ],
                        "filter"=>[
                            "bool"=>[
                                "must_not"=>[
                                    ["term" => [
                                        "record_status"=>[
                                            'value'=>0
                                        ]
                                    ]]
                                ]
                            ]

                        ]
                    ]
                ],
                "sort" => [
                    [
                        "last_name_transform" => [
                            "order" => "asc"
                        ]
                    ]
                ],
                "highlight" => [
                    "fields" => [
                        "fragments.except_content" => ["pre_tags" => "<strong>", "post_tags" => "</strong>", "number_of_fragments"=> 10000]
                    ]
                ]
            ]
        ];

        if($request->get('type')=='ti') {
            unset($array_search['body']['query']['bool']['must']);

            $array_search['body']['query']['bool']['must']['bool']['should'] = [
                ["match_phrase_prefix" => [
                    "images.all" => [
                        "query" => $request->get('phrase'),
                        "analyzer" => 'morfologik'
                    ]
                ]
                ],
                ["match_phrase_prefix" => [
                    "fragments.content" => [
                        "query" => $request->get('phrase'),
                        "analyzer" => 'morfologik'
                    ]
                ]
                ]
            ];
        }

//        return json_encode($array_search);


        $response = $this->elasticsearch->search($array_search);



        if($request->get('type')=='ti') {
            $std->records = $this->refactorToViewWhenImagesAndFragments($response['hits']['hits'], $phrase, $request->get('type'), $exept);
        }else{
            $std->records = $this->refactorToView($response['hits']['hits'], $phrase, $request->get('type'), $exept);
        }
        $std->total = $response['hits']['total'];
        $std->response = $response;
        $std->request = $request->all();

        return json_encode($std);

    }

    private function refactorToView($hits,$phrase,$type,$exept=[]){

        $array = [];


        foreach($hits as $hit){
            $std = new \stdClass();
            $std->record_id = $hit['_source']['id'];
            $std->record_title = $hit['_source']['record_title'];
            $std->record_alias = $hit['_source']['record_alias'];
            $std->last_name = $hit['_source']['last_name'];
            $std->duration = $hit['_source']['duration'];
            $std->type = $hit['_source']['type'];

//            if($type=='ti') {
//                $std->images = $this->getImagesMatchElastic($phrase, $hit['_source']['id']);
//            }

//            $std->hightlights = [];
//            foreach($hit['highlight']['fragments.content'] as $f){
//                array_push($std->hightlights,
//                    $this->searchForFragment($f, $hit['_source']['id'])
//                );
//            }


            $std->hightlights = $this->searchForFragmentGroup($phrase,$exept,$std->record_id);
            array_push($array,$std);
        }

        return $array;

    }


    private function refactorToViewWhenImagesAndFragments($hits,$phrase,$type,$exept=[]){


        $array = [];

        foreach($hits as $hit){

            $std = new \stdClass();
            $std->record_id = $hit['_source']['id'];
            $std->record_title = $hit['_source']['record_title'];
            $std->record_alias = $hit['_source']['record_alias'];
            $std->last_name = $hit['_source']['last_name'];
            $std->duration = $hit['_source']['duration'];
            $std->type = $hit['_source']['type'];
            $std->images = $this->getImagesMatchElastic($phrase, $hit['_source']['id']);


//            if(isset($hit['highlight'])) {
//                $std->hightlights = [];
//                foreach ($hit['highlight']['fragments.content'] as $f) {
//                    array_push($std->hightlights,
//                        $this->searchForFragment($f, $hit['_source']['id'])
//                    );
//                }
//            }

            $std->hightlights = $this->searchForFragmentGroup($phrase,$exept,$std->record_id);
            array_push($array,$std);
        }

        return $array;

    }

    private function searchForFragment($text,$rid){

        $phrase = str_replace('<strong>','',$text);
        $phrase = str_replace('</strong>','',$phrase);


        $array_search = [
            "index"=>"nagrania",
            "type"=>"fragmenty",
            "body" => [
                "query"=>[
                    "bool"=> [
                        "must"=> [
                            ["match_phrase_prefix"=> [
                                "content"=> $phrase
                            ]],
                            ["term"=>[
                                "record_id"=> [
                                    "value"=> $rid
                                ]
                            ]
                            ]
                        ]
                    ]
                ]
            ]
        ];


        $array_search = [
            "index"=>"nagrania",
            "type"=>"fragmenty",
            "body" => [
                "query"=>[
                    "bool"=> [
                        "must"=> [
                            ["match_phrase_prefix"=> [
                                "content"=> $phrase
                            ]],
                            ["term"=>[
                                "record_id"=> [
                                    "value"=> $rid
                                ]
                            ]
                            ]
                        ]
//                        "must_not"=>[
//                            ["match_phrase"=>[
//                                "content"=>[
//                                    "query"=>"o",
//                                    "analyzer"=>"english"
//                                ]
//                            ]]
//                        ]
                    ]
                ],
                "sort" => [
                    [
                        "start" => [
                            "order" => "asc"
                        ]
                    ]
                ],
                "highlight"=> [
                    "fields"=>[
                        "content"=>["pre_tags"=>"<strong>","post_tags"=>"</strong>"]
                    ]
                ]
            ]
        ];


        $response = $this->elasticsearch->search($array_search);



        if(!empty($response['hits']['hits'])) {
            return ['start' => $response['hits']['hits'][0]['_source']['start'], 'text' => $text];
        }

    }


    private function searchForFragmentGroup($phrase,$exept,$rid){


        $array_search = [
            "index"=>"nagrania",
            "type"=>"fragmenty",
            "body" => [
                "from" => 0,
                "size" => 3000,
                "query"=>[
                    "bool"=> [
                        "must"=> [
                            ["match_phrase_prefix"=> [
                                "except_content"=> [
                                    "query"=>$phrase,
                                    "analyzer" => 'my_custom_analyzer'
                                ]
                            ]],
                            ["term"=>[
                                "record_id"=> [
                                    "value"=> $rid
                                ]
                            ]
                            ]
                        ]
//                        "must_not"=>[
//                            ["match_phrase_prefix"=>[
//                                "content"=>[
//                                    "query"=>"o",
//                                    "analyzer"=>"simple"
//                                ]
//                            ]]
//                        ]
                    ]
                ],
                "sort" => [
                    [
                        "start" => [
                            "order" => "asc"
                        ]
                    ]
                ],
                "highlight"=> [
                    "fields"=>[
                        "content"=>["pre_tags"=>"<strong>","post_tags"=>"</strong>"]
                    ]
                ]
            ]
        ];

//        if(!empty($exept)){
//            $array_search['body']['query']['bool']['must_not'] = [];
//            foreach($exept as $w){
//                array_push(
//                    $array_search['body']['query']['bool']['must_not'],
//                    [
//                        "match_phrase_prefix"=>[
//                            "content"=>[
//                                "query"=>$w,
//                                "analyzer"=>"simple"
//                            ]
//                        ]
//                    ]
//                    );
//            }
//
//
//        }


        $response = $this->elasticsearch->search($array_search);

        return $response['hits']['hits'];


    }

//    public function searchByCriteria(Request $request){
//
//        $std = new \stdClass();
//
//        $phrase = $request->input('phrase');
//
//        $excepts = ['londyn'];
//        foreach($excepts as $val){
//            if($val == strtolower($phrase)){
//                $lang = 'english';
//                break;
//            }
//            $lang = 'polish';
//        }
//
//        if(strtolower($phrase)=='lwów'){
//
//            $lang = "polish_analyzer";
//
//            $array_search = [
//                "index" => "nagrania",
//                "type" => "fragmenty",
//                "body" => [
//                    "from" => $request->get('pag')['from'],
//                    "size" => $request->get('pag')['size'],
//                    "query" => [
//                        "bool" => [
//                            "should"=>[
//                                ["match_phrase_prefix" => [
//                                    "content" => [
//                                        "query" => $request->get('phrase'),
//                                        "analyzer" => $lang
//                                    ]
//                                ]
//                                ],
//                                ["match_phrase_prefix"=>[
//                                    "content"=>[
//                                        "query"=>$request->get('phrase'),
//                                        "analyzer"=>"ukrainian"//ukrainian
//                                    ]
//                                ]],
//                                ["match_phrase_prefix"=>[
//                                    "content"=>[
//                                        "query"=>$request->get('phrase'),
//                                        "analyzer"=>"polish"//ukrainian
//                                    ]
//                                ]]
//                            ]
//                        ]
//                    ],
//                    "sort" => [
//                        [
//                            "last_name" => [
//                                "order" => "asc"
//                            ]
//                        ]
//                    ],
//                    "highlight" => [
//                        "fields" => [
//                            "content" => ["pre_tags" => "<strong>", "post_tags" => "</strong>"]
//                        ]
//                    ]
//                ]
//            ];
//
//        }else {
//
//            $array_search = [
//                "index" => "nagrania",
//                "type" => "fragmenty",
//                "body" => [
//                    "from" => 0,//$request->get('pag')['from']
//                    "size" => $request->get('pag')['size'],
//                    "query" => [
//                        "bool" => [
//                            "must" => [
//                                ["match_phrase_prefix" => [
//                                    "content" => [
//                                        "query" => $request->get('phrase'),
//                                        "analyzer" => $lang
//                                    ]
//                                ]
//                                ]
//                            ]
//                        ]
//                    ],
//                    "sort" => [
//                        [
//                            "last_name" => [
//                                "order" => "asc"
//                            ]
//                        ]
//                    ],
//                    "highlight" => [
//                        "fields" => [
//                            "content" => ["pre_tags" => "<strong>", "post_tags" => "</strong>"]
//                        ]
//                    ]
//                ]
//            ];
//
//        }
//
//        $response = $this->elasticsearch->search($array_search);
//        $std->free_hits = $response['hits']['hits'];
//        $std->total = $response['hits']['total'];
//        $std->records = $this->refactorOrderbyRecord($response['hits']['hits'],$request->get('phrase'));
//
//        return response(json_encode($std),200,['Content-type'=>'application/json']);
//
//    }

    private function refactorOrderbyRecord($hits,$phrase){

        $array = [];

        foreach($hits as $hit){
            $hit['_source']['highlight'] = $hit['highlight'];
            array_push($array,$hit['_source']);
        }

        $collection = collect($array);

        $grouped = $collection->groupBy('record_id');

        $result = [];

        foreach($grouped->toArray() as $rid=>$record){
            $res = [];
            $res['name']=$record[0]['record_title'];
            $res['type']=$record[0]['type'];
            $res['duration']=$record[0]['duration'];
            $res['record_alias']=$record[0]['record_alias'];
            $res['record_id']=$record[0]['record_id'];
            $res['fragments']=$record;
            $res['images'] = $this->getImagesMatchElastic($phrase,$rid);
            array_push($result,$res);
        }

        return $result;
    }


    private function getImagesMatchElastic($phrase,$rid){

        $lang = 'polish';

        $array_search = [
            "index" => "images",
            "type" => "iptc_images",
            "body" => [
                "from" => 0,//$request->get('pag')['from']
                "size" => 2000,
                "query" => [
                    "bool" => [
                        "must" => [
                            "bool"=>[
                                "should"=>[
                                    ["match_phrase_prefix" => [
                                        "all"=>[
                                            "query" => $phrase,
                                            "analyzer" => 'morfologik'
                                        ]
                                    ]],
                                    ["match_phrase_prefix"=>[
                                        "all"=>[
                                            "query" => $phrase,
                                            "analyzer" => 'ukrainian'
                                        ]
                                    ]]
                                ]
                            ]
                        ],
                        "filter"=>[
                            "term" => [
                                "record_id"=>[
                                    'value'=>$rid
                                ]
                            ]
                        ]
                    ],
                ],
                "highlight" => [
                    "fields" => [
                        "all" => ["pre_tags" => "<strong>", "post_tags" => "</strong>"]
                    ]
                ]
            ]
        ];

        $response = $this->elasticsearch->search($array_search);

        return $response['hits']['hits'];


    }


    public function searchByIndexCriteriaNew(Request $request){

        $std = new \stdClass();



        $must = [];
        $must_f = [];

        if(!is_null($request->get('tag'))) {
            array_push($must, [
                "term" => [
                    "fragments.tags.id" => [
                        "value" => $request->get('tag')['id']
                    ]
                ]
            ]);
            array_push($must_f, [
                "term" => [
                    "tags.id" => [
                        "value" => $request->get('tag')['id']
                    ]
                ]
            ]);
        }

        if(!is_null($request->get('place'))) {
            array_push($must, [
                "term" => [
                    "fragments.places.id" => [
                        "value" => $request->get('place')['id']
                    ]
                ]
            ]);
            array_push($must_f, [
                "term" => [
                    "places.id" => [
                        "value" => $request->get('place')['id']
                    ]
                ]
            ]);
        }

        $should_must = [];
        $should_must_f = [];


        $intervals = $this->getIntervals($request);



        if(!is_null($intervals) || !empty($intervals)) {
            foreach ($intervals as $interval) {
                array_push($should_must, [
                    "term" => [
                        "fragments.intervals.id" => [
                            "value" => $interval->id
                        ]
                    ]
                ]);
                array_push($should_must_f, [
                    "term" => [
                        "intervals.id" => [
                            "value" => $interval->id
                        ]
                    ]
                ]);
            }
        }



        $in_must = [];

        if(!empty($should_must)) {
            $in_must['bool'] = [];
            $in_must['bool']['should'] = $should_must;
            $in_must_f['bool'] = [];
            $in_must_f['bool']['should'] = $should_must_f;
            array_push($must,$in_must);
            array_push($must_f,$in_must_f);
        }


        if(!is_null($request->get('tag')) && !is_null($request->get('place'))){

            $query = [
                "bool" => [
                    "must" => $must
                ]
            ];

            $query_f = [
                "bool" => [
                    "must" => $must_f
                ]
            ];

        }else{

            $query = [
                "bool" => [
                    "must" => $must
                ]
            ];

            $query_f = [
                "bool" => [
                    "must" => $must_f
                ]
            ];

        }

        if(count($intervals)!=0 || !is_null($request->get('tag')) || !is_null($request->get('place'))) {

            $q = [
                "index" => "records",
                "type" => "elements",
                "body" =>
                    [
                        "size" => $request->get('size'),
                        "from" => $request->get('from'),
                        "query" => $query,
                        "sort" => [
                            [
                                "last_name_transform" => [
                                    "order" => "asc"
                                ]
                            ]
                        ]
                    ]
            ];


            $q_f = [
                "index" => "records",
                "type" => "elements",
                "body" =>
                    [
                        "size" => $request->get('size'),
                        "from" => $request->get('from'),
                        "query" => $query_f,
                        "sort" => [
                            [
                                "last_name_transform" => [
                                    "order" => "asc"
                                ]
                            ]
                        ]
                    ]
            ];


            $response = $this->elasticsearch->search($q);
            $std->total = $response['hits']['total'];
            $std->query = $q;
            $std->records = $this->searchForIndexedFragmentOfRecord($response['hits']['hits'], $q_f);
//            $std->records = $response['hits']['hits'];

        }else{
            $std->total = 0;
        }


        return response(json_encode($std),200,['Content-type'=>'application/json']);

    }



    private function searchForIndexedFragmentOfRecord($hits,$q){


        $elms = [];

        foreach($hits as $k=>$h){

            $qo = $q;

            $qo['index'] = 'nagrania';
            $qo['type']= 'fragmenty';
            $qo['body']['from'] = 0;
            $qo['body']['size'] = 3000;



            $qo['body']['query']['bool']['must'][] = [
                "term"=>[
                    "record_id" => [
                        "value" => $h['_source']['id']
                    ]
                ]
            ];

//            return $qo;

            $response = $this->elasticsearch->search($qo);
//
            $res = [];

            foreach($response['hits']['hits'] as $r){
                array_push($res,$r['_source']);
            }

            array_push($elms, [
                'type'=>$h['_source']['type'],
                'duration'=>$h['_source']['duration'],
                'name'=>$h['_source']['record_title'],
                'record_alias'=>$h['_source']['record_alias'],
                'record_id'=>$h['_source']['id'],
                'fragments'=>$res
            ]);
        }

        return $elms;

    }


    /*
     * INDEX
     */


    public function searchByIndexCriteria(Request $request){

//        return $request->all();

        $std = new \stdClass();



        $must = [];

        if(!is_null($request->get('tag'))) {
            array_push($must, [
                "term" => [
                    "tags.id" => [
                        "value" => $request->get('tag')['id']
                    ]
                ]
            ]);
        }

        if(!is_null($request->get('place'))) {
            array_push($must, [
                "term" => [
                    "places.id" => [
                        "value" => $request->get('place')['id']
                    ]
                ]
            ]);
        }

        $should_must = [];


        $intervals = $this->getIntervals($request);



        if(!is_null($intervals) || !empty($intervals)) {
            foreach ($intervals as $interval) {
                array_push($should_must, [
                    "term" => [
                        "intervals.id" => [
                            "value" => $interval->id
                        ]
                    ]
                ]);
            }
        }



        $in_must = [];

        if(!empty($should_must)) {
            $in_must['bool'] = [];
            $in_must['bool']['should'] = $should_must;
            array_push($must,$in_must);
        }


        if(!is_null($request->get('tag')) && !is_null($request->get('place'))){

            $query = [
                "bool" => [
                    "must" => $must
                ]
            ];

        }else{

            $query = [
                "bool" => [
                    "must" => $must
                ]
            ];

        }

        if(count($intervals)!=0 || !is_null($request->get('tag')) || !is_null($request->get('place'))) {

            $q = [
                "index" => "nagrania",
                "type" => "fragmenty",
                "body" =>
                    [
                        "size" => $request->get('size'),
                        "from" => $request->get('from'),
                        "query" => $query,
                        "sort" => [
                            [
                                "last_name_transform" => [
                                    "order" => "asc"
                                ]
                            ]
                        ]
                    ]
            ];


            $response = $this->elasticsearch->search($q);
            $std->total = $response['hits']['total'];
            $std->query = $q;
            $std->records = $this->refactorOrderbyRecordIndex($response['hits']['hits']);

        }else{
            $std->total = 0;
        }


        return response(json_encode($std),200,['Content-type'=>'application/json']);

    }


    private function refactorOrderbyRecordIndex($hits){

        $array = [];

        foreach($hits as $hit){
            array_push($array,$hit['_source']);
        }

        $collection = collect($array);

        $grouped = $collection->groupBy('record_id');

        $result = [];

        foreach($grouped->toArray() as $name=>$record){
            $res = [];
            $res['name']=$record[0]['record_title'];
            $res['type']=$record[0]['type'];
            $res['duration']=$record[0]['duration'];
            $res['record_alias']=$record[0]['record_alias'];
            $res['record_id']=$record[0]['record_id'];
            $res['fragments']=$record;

            array_push($result,$res);
        }

        return $result;
    }


    /*
     * INDEX
     */


    private function getIntervals($request)
    {
        if($request->get('date')['begin']['year']!="" && $request->get('date')['begin']['month']!="" && $request->get('date')['begin']['day']!=""){

            $begin = Carbon::createFromDate($request->get('date')['begin']['year'], $request->get('date')['begin']['month'], $request->get('date')['begin']['day'])->toDateString();

        }elseif($request->get('date')['begin']['year']!="" && $request->get('date')['begin']['month']!="" && $request->get('date')['begin']['day']==""){

            $begin = Carbon::createFromDate($request->get('date')['begin']['year'], $request->get('date')['begin']['month'], 1)->toDateString();

        }elseif($request->get('date')['begin']['year']!="" && $request->get('date')['begin']['month']=="" && $request->get('date')['begin']['day']==""){

            $begin = Carbon::createFromDate($request->get('date')['begin']['year'], 1, 1)->toDateString();

        }else{

            $begin = null;

        }



        if($request->get('date')['end']['year']!="" && $request->get('date')['end']['month']!="" && $request->get('date')['end']['day']!=""){

            $end = Carbon::createFromDate($request->get('date')['end']['year'], $request->get('date')['end']['month'], $request->get('date')['end']['day'])->toDateString();

        }elseif($request->get('date')['end']['year']!="" && $request->get('date')['end']['month']!="" && $request->get('date')['end']['day']==""){

            $month_days = CustomHelp::checkMonthDaysByYearAndMonth($request->get('date')['end']['year'],$request->get('date')['end']['month']);
            $end = Carbon::createFromDate($request->get('date')['end']['year'], $request->get('date')['end']['month'], $month_days)->toDateString();

        }elseif($request->get('date')['end']['year']!="" && $request->get('date')['end']['month']=="" && $request->get('date')['end']['day']==""){

            $end = Carbon::createFromDate($request->get('date')['end']['year'], 12, 31)->toDateString();

        }else{

            $end = null;

        }


        if (is_null($begin) && !is_null($end)) {

            $intervals = DB::table('intervals')
                ->whereDate('end', '<=', $end)->get();

        } elseif (!is_null($begin) && is_null($end)) {

            $intervals = DB::table('intervals')
                ->whereDate('begin', '>=', $begin)->get();

        } elseif (!is_null($begin) && !is_null($end)) {

            $intervals = DB::table('intervals')
                ->whereDate('end', '<=', $end)
                ->whereDate('begin', '>=', $begin)->get();
        } else {

            $intervals = null;

        }

        return $intervals;

    }



//    private function makeDateAccident($intervals){
//
//        $table = [];
//
//        foreach($intervals as $inter){
//
//            $table['begin'] = [];
//            $table['end'] = [];
//
//            $stdB = new \stdClass();
//            $stdE = new \stdClass();
//
//            if(!is_null($inter->begin)){
//                $exB = explode('-',$inter->begin);
//                $stdB->year = $exB[0];
//                $stdB->month = $exB[1];
//                $stdB->day = $exB[2];
//                array_push($table['begin'],$stdB);
//            }else{
//
//            }
//
//            if(!is_null($inter->end)){
//                $exE = explode('-',$inter->end);
//                $stdE->year = $exE[0];
//                $stdE->month = $exE[1];
//                $stdE->day = $exE[2];
//                array_push($table['end'],$stdE);
//            }else{
//
//            }
//
//        }
//
//        return $table;
//
//    }



    public function getAlphabetIndex(){

        return range('A','Z');

    }

    public function getPlacesByLetter(Request $request){

        return Place::where('name','LIKE',$request->get('letter').'%')->orWhere('name','LIKE',strtolower($request->get('letter')).'%')->orderBy('name')->get();

    }

    public function getTagsByLetter(Request $request){

        return Tag::where('name','LIKE',$request->get('letter').'%')->orWhere('name','LIKE',strtolower($request->get('letter')).'%')->orderBy('name')->get();

    }

    public function getIntervalsByLetter(Request $request){

        return Interval::where(function($q) use ($request) {
            $q->where('name','LIKE',$request->get('letter').'%');
            $q->orWhere('name','LIKE',$request->get('letter').'%');
        })->where('name','!=','')->orderBy('name')->get();

    }

}
