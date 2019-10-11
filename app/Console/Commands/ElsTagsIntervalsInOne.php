<?php

namespace App\Console\Commands;

use App\Entities\Record;
use Illuminate\Console\Command;
use Elasticsearch\Client;
use App\Entities\Fragment;
use App\Entities\Tag;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class ElsTagsIntervalsInOne extends Command
{

    private $elasticsearch;
    private $curl;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'els:tags:intevals:one';

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
        $map = Storage::disk('es_prop')->get('nagrania_fragmenty_map.json','Content');

        $response = Curl::to('http://localhost:9200/nagrania')
            ->withData( json_decode($map,true) )
            ->asJson()
            ->put();

        $this->info(json_encode($response));

        $frgs = Fragment::all();
//        $frgs = Fragment::where('content','LIKE','%Lwów%')->get();
        $count_frgs = $frgs->count();

        foreach($frgs as $f){

            $this->info('ID: '.$f->id);

            $this->elasticsearch->index([
                'index'=>'nagrania',
                'type'=>'fragmenty',
                'id'=>$f->id,
                'body'=>$this->prepareBodyIndex($f)
            ]);

            $this->info('Fragment: '.$count_frgs);

            $count_frgs--;


        }

    }


    private function prepareBodyIndex($f){

        $array = [];

        $r = $f->record()->first();

        $array['fid'] = $f->id;
        $array['record_id'] = $r->id;
        $array['record_title'] = $r->title;
        $array['record_alias'] = $r->alias;
        $array['content'] = strip_tags($f->content);
        $array['start'] = $f->start;
        $array['type'] = Record::find($f->record_id)->type;
        $array['duration'] = Record::find($f->record_id)->duration;

        $array['intervals'] = [];
        foreach($f->intervals()->get() as $inter){
            array_push($array['intervals'],[
                'id'=>$inter->id,
                'begin'=>$inter->begin,
                'end'=>$inter->end
            ]);
        }

        $array['tags'] = [];
        foreach($f->tags()->get() as $tag){
            array_push($array['tags'],[
                'id'=>$tag->id,
                'name'=>$tag->name
            ]);
        }

        $array['places'] = [];
        foreach($f->places()->get() as $tag){
            array_push($array['tags'],[
                'id'=>$tag->id,
                'name'=>$tag->name
            ]);
        }

        return $array;
    }

}
