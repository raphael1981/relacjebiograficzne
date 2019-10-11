<?php

namespace App\Console\Commands;

use App\Entities\Fragment;
use App\Entities\Interviewee;
use App\Entities\Record;
use Illuminate\Console\Command;
use Mmanos\Search\Facade as Search;
use Illuminate\Support\Facades\DB;

class IndexSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:index {--action=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index Lucene Search';

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
        $action = $options['action'];
        $frase = 'Warszawa';

        switch($action){

            case 'delete':

                Search::deleteIndex();

                $this->info('Delete index');

                break;

            case 'index':

                $sql = DB::table('records')
                    ->select(
                        'fragments.id',
                        'records.id as rid',
                        'records.title',
                        'records.type',
                        'records.alias',
                        'fragments.start',
                        'fragments.content',
                        DB::raw('round(
                    (
                        length(`fragments`.`content`) -
                        length(replace(`fragments`.`content`, "'.$frase.'", ""))
                    )
                    /
                    length("'.$frase.'")
                    ) as weight'),
                        DB::raw('replace(`fragments`.`content`, "'.$frase.'", "<b><i>'.$frase.'</i></b>") as signcontent')
                    )
                    ->join('fragments', 'records.id', '=', 'fragments.record_id')
                    ->where('fragments.content', 'RLIKE', ''.$frase)
                    ->groupBy('records.id','fragments.id','fragments.content')
                    ->orderBy('weight', 'desc');


                $data = $sql->get();

                $array = [];

                foreach($data as $k=>$r){

                    if(key_exists($r->rid, $array)){

                        $array[$r->rid]['fragments'][] = [
                            'id'=>$r->id,
                            'start'=>$r->start,
                            'signcontent'=>$r->signcontent,
//                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
                        ];


                    }else{
                        $array[$r->rid] = [];
                        $array[$r->rid]['record'] = [
                            'title'=>$r->title,
                            'rid'=>$r->rid,
                            'type'=>$r->type,
                            'alias'=>$r->alias
//                    'interviewee'=>Record::find($r->rid)->interviewees()->get()
                        ];

                        $array[$r->rid]['fragments'] = [];
                        $array[$r->rid]['fragments'][] = [
                            'id'=>$r->id,
                            'start'=>$r->start,
                            'signcontent'=>$r->signcontent,
//                    'interviewee'=>Fragment::find($r->id)->interviewees()->get()
                        ];

                    }

                }


                foreach($array as $key=>$record){

                    Search::index('records')->insert($key, [
                        'frase'=>$frase,
                        'title'=>$record['record']['title'],
                        'rid'=>$record['record']['rid'],
                        'type'=>$record['record']['type'],
                        'alias'=>$record['record']['alias'],
//                        'fragments' => json_encode($record['fragments']),
                        'interviewees'=>Record::find($record['record']['rid'])->interviewees()->get()->toArray()
                    ]);

                    $this->info('Title:'.$record['record']['title'].' Id:'.$key.' was index');

                }


                $this->info('Index make');


                break;

            default:

                $this->info('No action');

                break;

        }




    }
}
