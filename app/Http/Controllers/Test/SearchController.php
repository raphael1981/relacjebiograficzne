<?php
/**
 * Created by PhpStorm.
 * User: rradecki
 * Date: 2017-03-29
 * Time: 10:00
 */
namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories;
use Ixudra\Curl\Facades\Curl;
use Elasticsearch\Client;

class SearchController
{

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }


    public function searchIndex(Request $request){

/*        $query = "SELECT fr.id as frag_id,
                         fr.record_id as rec_id,
                         fr.content as content 
                         FROM fragments as fr
                         left join intervalgables as intgab on fr.id=intgab.intervalgables_id
                         left join intervals as intval on intgab.interval_id=intval.id
						 and intval.name like '%Powstanie Warszawskie%'
                         and intgab.intervalgables_type like '%Fragment%'
                         left join placegables as plgb on fr.id=plgb.placegables_id
                         left join places as pl on plgb.place_id=pl.id                         
                         and pl.name like '%South Kristoferport%' 
                         and plgb.placegables_type like '%Fragment%' limit 10";*/


        $query = "SELECT fr.id as frag_id,
                         fr.record_id as rec_id,
                         fr.content as content 
                         FROM fragments as fr
                         left join intervalgables as intgab on fr.id=intgab.intervalgables_id
                         left join intervals as intval on intgab.interval_id=intval.id
                         left join placegables as plgb on fr.id=plgb.placegables_id
                         left join places as pl on plgb.place_id=pl.id
						 where intval.name like '%Powstanie Warszawskie%'
                         and intgab.intervalgables_type like '%Fragment%'
                         and pl.name like '%South Kristoferport%' 
                         and plgb.placegables_type like '%Fragment%' limit 10";


        $results = DB::select( DB::raw($query));


        $testsearch = view('test.search',['intervals'=>\GuzzleHttp\json_encode($results)]);

        return view('test.master', [
            'content' => $testsearch,
            'title'=>'Relacje biograficzne - wyszukiwanie zaawansowane',
            'controller'=>'test/test.controller.js'
        ]);
    }

        public function getElasticSearch(Request $request,$phrase){
            $result = $this->elasticsearch->search([
                'index' => 'biorel',
                'type' => 'fragment',
                'body' => json_decode('{"size":100,"query":{"match_phrase" : {"content" : {"query" :"'.$phrase.'","analyzer" : "polish"}}}}',true)
            ]);

            return json_encode($result);
        }


       public function searchElasticIndex(Request $request){
           return view('test.master', [
                    'content' => view('test.elasticsearch'),
                    'title'=>'Relacje biograficzne - wyszukiwanie testowe',
                    'controller'=>'test/test.controller.js'
                ]);
            }


       public function searchElasticForRecord(Request $request){
           $id = $request->input('id');
           $phrase = $request->input('phrase');

           $excepts = ['londyn'];
           foreach($excepts as $val){
               if($val == strtolower($phrase)){
                        $lang = 'english';
                        break;
                   }
               $lang = 'polish';
           }


           $query = '{"size":100,
              "query": {
                    "bool": {
                        "must": [
                            {"match_phrase_prefix": {
                                    "content": "'.$phrase.'"
                               }},
                        {"term": {
                            "record_id": {
                             "value": "'.$id.'"
                        }
                    }}
                    ]
                    }
                },
                 "sort" : ["start"],
                 "highlight":{
                    "fields": {
                            "content": {"pre_tags":"|%","post_tags":"%|"}
                               }
                            }
                }';

           $query_op = '{
                        "size":100,
                        "query":{
                        "bool":{  
                        "must":[  
                            {"match" : {
                                    "content" : {
                                        "query" : "'.$phrase.'",
                                        "analyzer" : "'.$lang.'",
                                        "operator": "and"
                                        } 
                                    }},
                            {"term": {"record_id": {
                                        "value": "'.$id.'"   
                                    }
                                }}      
                                    ]
                                }
                        },
                        "sort": ["start"],
                            "highlight":{
                            "fields": {
                        "content": {"pre_tags":"|%","post_tags":"%|"}
                                                       }
                                                  }
                        }';

           $result = $this->elasticsearch->search([
               'index' => 'nagrania',
               'type' => 'fragmenty',
               'body' => json_decode($query,true)
           ]);

           return $result;

       }
}