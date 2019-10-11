<?php

namespace App\Console\Commands;

use App\Entities\Fragment;
use App\Entities\Place;
use Illuminate\Console\Command;
use GuzzleHttp\Ring\Client\MockHandler;
use Elasticsearch\Client;

class LuceneIndexPlacesAll extends Command
{

    private $elasticsearch;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'places:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Place index all';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $all_fragments = [];
        $records_arr = [];

        $places = Place::all();

        foreach($places as $key=>$place) {

            $all_fragments[$place->id] = Place::find($place->id)->fragments()->get();
            $records_arr[$place->id] = [];

            foreach ($all_fragments[$place->id] as $k => $f) {

                $r = Fragment::find($f->id)->record()->first();

                if (key_exists($r->id, $records_arr[$place->id])) {

                    if ($f->record_id == $r->id) {
                        array_push($records_arr[$place->id][$r->id]['fragments'], $f);
                    }

                } else {

                    $records_arr[$place->id][$r->id]['record'] = $r;
                    $records_arr[$place->id][$r->id]['fragments'] = [];
                    if ($f->record_id == $r->id) {
                        array_push($records_arr[$place->id][$r->id]['fragments'], $f);
                    }
                }

            }

        }

        $res = [];

        foreach($records_arr as $key=>$place_array){

            Cache::forever($key, $place_array);

        }
    }
}
