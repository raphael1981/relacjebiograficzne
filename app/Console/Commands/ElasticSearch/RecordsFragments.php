<?php

namespace App\Console\Commands\ElasticSearch;

use Illuminate\Console\Command;
use App\Entities\Record;
use Elasticsearch\Client;
use CSD\Image\Image as ImageCSD;
use App\Entities\Fragment;
use App\Entities\Tag;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class RecordsFragments extends Command
{
    private $elasticsearch;
    private $curl;
    private $letters = ['ą','ę','ć','ś','ł','ń','ź','ż'];
    private $letters_to_replace = ['a','e','c','s','l','n','z','z'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'els:records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $map = Storage::disk('es_prop')->get('group_nagrania_fragmenty.json','Content');

        $response = Curl::to('http://localhost:9200/records')
            ->withData( json_decode($map,true) )
            ->asJson()
            ->put();

        $this->info(json_encode($response));

        foreach(Record::all() as $key=>$record){

//            if($record->status==1) {

                $interviewee = $record->interviewees()->first();

                if(count($interviewee)>0) {

                    $this->elasticsearch->index([
                        'index' => 'records',
                        'type' => 'elements',
                        'id' => $record->id,
                        'body' => $this->prepareBodyIndex($record, $interviewee)
                    ]);

                    $this->info('RECORD:' . $record->id);

                }

//            }

        }

        $this->call('els:fragments', []);

    }


    private function prepareBodyIndex($r,$interviewee){

        $ex_title = explode(" ",$r->title);

        if(count($ex_title)>2){
            $last_name = $ex_title[1];
        }else{
            $last_name = $ex_title[1];
        }


        $array['id'] = $r->id;
        $array['record_title'] = $r->title;
        $array['last_name'] = $interviewee->surname;
        $array['last_name_transform'] = $this->makeNameTransform($interviewee->surname).$this->makeNameTransform($interviewee->name);
        $array['record_alias'] = $r->alias;
        $array['type'] = $r->type;
        $array['duration'] = $r->duration;
        $array['record_status'] = $r->status;

        $array['fragments'] = [];
        foreach($r->fragments()->get() as $f){
            array_push($array['fragments'],[
                'fid'=>$f->id,
                'content'=>strip_tags($f->content),
                'start'=>$f->start,
                "intervals"=>$this->getFragmentIntervals($f),
                "tags"=>$this->getFragmentTags($f),
                "places"=>$this->getFragmentPlaces($f)
            ]);
        }

        return $array;

    }


    private function getFragmentIntervals($f){

        $array = [];

        foreach($f->intervals()->get() as $inter){
            array_push($array,[
                'id'=>$inter->id,
                'begin'=>$inter->begin,
                'end'=>$inter->end
            ]);
        }

        return $array;

    }


    private function getFragmentTags($f){

        $array = [];

        foreach($f->tags()->get() as $tag){
            array_push($array,[
                'id'=>$tag->id,
                'name'=>$tag->name
            ]);
        }

        return $array;

    }


    private function getFragmentPlaces($f){

        $array = [];

        foreach($f->places()->get() as $tag){
            array_push($array,[
                'id'=>$tag->id,
                'name'=>$tag->name
            ]);
        }

        return $array;

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
