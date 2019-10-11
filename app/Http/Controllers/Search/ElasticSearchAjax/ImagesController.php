<?php

namespace App\Http\Controllers\Search\ElasticSearchAjax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Elasticsearch\Client;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class ImagesController extends Controller
{


    private $elasticsearch;
    private $curl;


    public function __construct(Client $elasticsearch, Curl $curl)
    {

        $this->elasticsearch = $elasticsearch;
        $this->curl = $curl;

    }

    public function getSearchImagesElastic(Request $request){

//        $type_map = '{
//                    "size":2000,
//                    "query": {
//                        "match_phrase_prefix": {
//                            "all":"'.$request->get('frase').'"
//                        }
//                    },
//                    "highlight" : {
//                      "fields" : {
//                          "all" : { "pre_tags" : ["<strong>"], "post_tags" : ["</strong>"] }
//                      }
//                  }
//                }';

        $type_map_array = [
            "index"=>"images",
            "type"=>"iptc_images",
            "body"=>[
                "size"=> 2000,
                "query"=> [
                    "bool"=>[
                        "must"=>[
                            "bool"=>[
                                "should"=>[
                                    ["match_phrase_prefix"=>[
                                        "all"=>[
                                            "query" => $request->get('frase'),
                                            "analyzer" => 'morfologik'
                                        ]
                                    ]],
//                                    ["match_phrase_prefix"=>[
//                                        "all"=>[
//                                            "query" => $request->get('frase'),
//                                            "analyzer" => 'polish'
//                                        ]
//                                    ]],
                                    ["match_phrase_prefix"=>[
                                        "all"=>[
                                            "query" => $request->get('frase'),
                                            "analyzer" => 'ukrainian'
                                        ]
                                    ]]
                                ]
                            ]
                        ],
                        "filter"=>[
                            "term" => [
                                "record_status"=>[
                                    'value'=>1
                                ]
                            ]
                        ]
                    ]
                ],
                "highlight"=>[
                    "fields"=>[
                        "all" =>[ "pre_tags" => ["<strong>"], "post_tags" => ["</strong>"] ]
                    ]
                ]
            ]
        ];




//        $type_map_array = [
//            "index"=>"images",
//            "type"=>"iptc_images",
//            "body"=>[
//                "size"=> 2000,
//                "query"=> [
//                    "match_phrase_prefix"=>[
//                        "all"=>[
//                            "query" => strtolower($request->get('frase')),
//                            "analyzer" => 'morfologik'
//                        ]
//                    ]
//                ],
//                "highlight"=>[
//                    "fields"=>[
//                        "all" =>[ "pre_tags" => ["<strong>"], "post_tags" => ["</strong>"] ]
//                    ]
//                ]
//            ]
//        ];

//        $type_map_array = [
//            "index"=>"images",
//            "type"=>"iptc_images",
//            "body"=>[
//                "size"=> 2000,
//                "query"=> [
//                    "bool"=>[
//                        "must"=>[
//                            ["match_phrase_prefix"=>[
//                                "all"=>[
//                                    "query" => $request->get('frase'),
//                                    "analyzer" => 'ukrainian'
//                                ]
//                            ]]
//                        ]
//                    ]
//                ],
//                "highlight"=>[
//                    "fields"=>[
//                        "all" =>[ "pre_tags" => ["<strong>"], "post_tags" => ["</strong>"] ]
//                    ]
//                ]
//            ]
//        ];


        $response = $this->elasticsearch->search($type_map_array);

//        $response = Curl::to('http://localhost:9200/images/_search')
//            ->withData( $type_map )
//            ->get();

        $decode_res = \GuzzleHttp\json_decode(json_encode($response));

//        return json_encode($decode_res);

        $array = [];

        foreach($decode_res->hits->hits as $hit){

//            $hit->_source->records = $this->refactorRecordFragments($hit->_source->fragments);
            $hit->_source->highlight =$hit->highlight;
            array_push($array,$hit->_source);

        }

        return $array;
    }


    private function refactorRecordFragments($frgs){

        $collection = collect($frgs);

        $grouped = $collection->groupBy('record_id');

        $array = [];

        foreach($grouped->toArray() as $rid=>$rec){

            $std = new \stdClass();
            $std->rid = $rid;
            $std->record_title = $rec[0]->record_title;
            $std->record_type = $rec[0]->record_type;
            $std->record_alias = $rec[0]->record_alias;
            $std->fragments = [];


            foreach($rec as $f) {

                array_push($std->fragments, [
                    'rid' => $rid,
                    'fid' => $f->fid,
                    'content' => $f->fragment_content,
                    'record_title' => $f->record_title,
                    'start' => $f->start
                ]);

            }


            array_push($array,$std);

        }

        return $array;

    }

}
