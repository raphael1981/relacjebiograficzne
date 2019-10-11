<?php

namespace App\Console\Commands;

use App\Entities\Record;
use Illuminate\Console\Command;
use Elasticsearch\Client;
use App\Entities\Fragment;
use App\Entities\Tag;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class ElsRecordFragmentInOne extends Command
{
    private $elasticsearch;
    private $curl;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'els:record:fragment';

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
        $map = Storage::disk('es_prop')->get('record_fragment_map.json','Content');
        $type_map = str_replace('<name_of_type>', 'element', $map);
        $response = Curl::to('http://localhost:9200/records')
            ->withData( json_decode($type_map,true) )
            ->asJson()
            ->put();

        $recs = Record::all();
        $recs_count = $recs->count();

        foreach($recs as $k=>$rec){

            $data = $this->prepareDataToIndex($rec);

            $this->elasticsearch->index([
                'index' => 'records',
                'type' => 'element',
                'id' => $rec->id,
                'body' => $data
            ]);

            $this->info('Record: '.$recs_count);
            $recs_count--;
        }

    }

    public function prepareDataToIndex($rec){

        $array = [];
        $array['id'] = $rec->id;
        $array['title'] = $rec->title;
        $array['fragments'] = [];

        foreach($rec->fragments()->get() as $f){
            array_push(
                $array['fragments'],
                    [
                        "fid"=>$f->id,
                        "record_id"=>$f->record_id,
                        "start"=>$f->start,
                        "content"=>$f->content
                    ]
                );
        }


        return $array;

    }

}
