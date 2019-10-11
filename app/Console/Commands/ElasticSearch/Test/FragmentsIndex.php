<?php

namespace App\Console\Commands\ElasticSearch\Test;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Entities\Record;
use Elasticsearch\Client;
use CSD\Image\Image as ImageCSD;
use App\Entities\Fragment;
use App\Entities\Tag;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class FragmentsIndex extends Command
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
    protected $signature = 'test:els:fragments';

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
        $this->deleteIndex('testfragments');

        $array = [];

        foreach(Record::all() as $r){
            array_push($array,$r->title);
        }

        $title = $this->anticipate('Podaj nazwę nagrania', $array);

        $r = Record::where('title',$title)->first();

        $map = Storage::disk('es_prop')->get('test/fragments.json','Content');

        $response = Curl::to('http://localhost:9200/testfragments')
            ->withData( json_decode($map,true) )
            ->asJson()
            ->put();

        foreach($r->fragments()->orderBy('start')->get() as $f) {


            $this->elasticsearch->index([
                'index' => 'testfragments',
                'type' => 'elements',
                'id' => $f->id,
                'body' => $this->prepareBodyIndex($f)
            ]);


            $this->info($f->start);

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

        return $array;

    }


    private function deleteIndex($name){
        $response = Curl::to('http://localhost:9200/'.$name)->delete();
    }

}
