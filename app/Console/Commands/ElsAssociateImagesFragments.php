<?php

namespace App\Console\Commands;

use App\Entities\Place;
use App\Entities\Tag;
use Illuminate\Console\Command;
use CSD\Image\Image as ImageCSD;
use Elasticsearch\Client;
use App\Entities\Picture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class ElsAssociateImagesFragments extends Command
{
    private $elasticsearch;
    private $curl;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'els:associate:images:fragments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Associate images and fragments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $elasticsearch, Curl $curl)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
        $this->curl = $curl;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $update_json = Storage::disk('es_prop')->get('update_images_with_fragments.json','Content');

        $tags = Tag::all();
        $places = Place::all();
        $result = $tags->merge($places);

        foreach($result as $k=>$t){

            $type_map = '{
                    "index":"images",
                    "type":"iptc_images",
                    "body":{
                        "size": 2000,
                        "query": {
                            "match_phrase_prefix": {
                                "all":"'.str_replace('"','',$t->name).'"
                            }
                        }
                    }
                }';

            $type_map_array = [
                    "index"=>"images",
                    "type"=>"iptc_images",
                    "body"=>[
                        "size"=> 4000,
                        "query"=>[
                          "bool"=>[
                              "should"=>[
                                  ["match_phrase_prefix"=>[
                                      "all"=>str_replace('"','',$t->name)
                                  ]]
                              ]
                          ]
                        ]
                    ]
                ];

            $response = $this->elasticsearch->search($type_map_array);

//            $this->info('-----------------------------------------------------------------------------------------------');
//            $this->info($t->name);
//            $this->info('-----------------------------------------------------------------------------------------------');

            $response = \GuzzleHttp\json_decode(json_encode($response));
//            $this->info('-----------------------------------------------------------------------------------------------');
//            $this->info('Hits: '.count($response->hits->hits));
//            $this->info('-----------------------------------------------------------------------------------------------');

            foreach ($response->hits->hits as $k => $img) {

                foreach($t->fragments()->get() as $f){

                    $fragments_json = json_encode($this->refactorToElasticProperties($f));
                    $json_add_struct = str_replace("<array>",$fragments_json, $update_json);

                    $this->info($img->_id);
                    $this->info($json_add_struct);
                    $response = Curl::to('http://localhost:9200/images/iptc_images/'.$img->_id.'/_update')
                        ->withData( \GuzzleHttp\json_decode($json_add_struct,true))
                        ->asJson()
                        ->post();

//                    $this->info('Fragment ID: '.$f->id);

                }



//                $this->info('Index: '.$k);

            }


        }



    }


    private function refactorToElasticProperties($data){

        $array = [];
        $array['fid'] = $data->id;
        $array['start'] = $data->start;
        $r = $data->record()->first();
        $array['record_id'] = $r->id;
        $array['record_title'] = $r->title;
        $array['record_alias'] = $r->alias;
        $array['record_type'] = $r->type;
        $array['fragment_content'] = $data->content;

        return $array;

    }



}
