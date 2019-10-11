<?php

namespace App\Console\Commands;

use App\Entities\Fragment;
use App\Entities\Place;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Mmanos\Search\Facade as Search;
use Illuminate\Support\Facades\DB;

class LuceneIndexPlaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'places:index:id {--placeid=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index Places';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();
        $pid = $options['placeid'];

        $records_arr = [];

        $all_fragments = Place::find($pid)->fragments()->get();

        $this->info($all_fragments,1);

        foreach($all_fragments as $k=>$f) {

            $r = Fragment::find($f->id)->record()->first();

            if (key_exists($r->id, $records_arr)) {

                if ($f->record_id == $r->id) {
                    array_push($records_arr[$r->id]['fragments'], $f);
                }

            } else {

                $records_arr[$r->id]['record'] = $r;
                $records_arr[$r->id]['fragments'] = [];
                if ($f->record_id == $r->id) {
                    array_push($records_arr[$r->id]['fragments'], $f);
                }
            }

        }

        $res = [];

        foreach($records_arr as $key=>$arr){

            $res[$key] = [];

            foreach ($arr as $k=>$r){

                $res[$key][$k] = $r;
                $this->info(print_r($res[$key][$k],1));

            }

        }



        $this->info('index id: '.$pid.' create',1);
        Cache::forever($pid, $res);


    }
}
