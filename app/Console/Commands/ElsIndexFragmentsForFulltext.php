<?php

namespace App\Console\Commands;

use App\Entities\Record;
use Illuminate\Console\Command;
use Elasticsearch\Client;
use App\Entities\Fragment;
use App\Entities\Tag;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class ElsIndexFragmentsForFulltext extends Command
{

    private $elasticsearch;
    private $curl;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'els:index:fragments:fulltext';

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
        $map = Storage::disk('es_prop')->get('transcription_mapping.json','Content');

        $type_map = str_replace('<name_of_type>', 'element', $map);
        $response = Curl::to('http://localhost:9200/fragments')
            ->withData( json_decode($type_map,true) )
            ->asJson()
            ->put();

        $frgs = Fragment::all();
        $frgs_count = $frgs->count();

        foreach($frgs as $k=>$f){

            $data = $this->refactorToElasticProperties($f);

            $this->elasticsearch->index([
                'index' => 'fragments',
                'type' => 'element',
                'id' => $f->id,
                'body' => $data
            ]);

            $this->info('Fragmenty: '.$frgs_count);

            $frgs_count--;

        }



    }


    private function refactorToElasticProperties($data){

        $array = [''];
        $array['fid'] = $data->id;
        $array['content'] = strip_tags($data->content);
        $array['start'] = $data->start;
        $r = $data->record()->first();
        $array['record_id'] = $r->id;
        $array['record_title'] = $r->title;

        return $array;

    }


}
