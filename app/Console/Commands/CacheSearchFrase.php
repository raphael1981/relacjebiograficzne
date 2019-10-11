<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheSearchFrase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'frase:cache {frase}';

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

        $frase = $this->argument('frase');

        $sql = DB::table('records')
            ->select(
                'fragments.id',
                'records.id as rid',
                'records.title',
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

        $array = array_chunk($array,3);

        Cache::forever($frase, $array);

        return $array;
    }
}
