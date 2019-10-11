<?php

namespace App\Console\Commands\Interviewee;

use App\Entities\Interviewee;
use App\Entities\Record;
use Illuminate\Console\Command;

class SortStringMake extends Command
{

    private $letters = ['ą','ę','ć','ś','ł','ń','ź','ż'];
    private $letters_to_replace = ['a','e','c','s','l','n','z','z'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interviewee:sort';

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
        foreach(Interviewee::all() as $k=>$inter){

            $string = $this->makeNameTransform($inter->surname).$this->makeNameTransform($inter->name);

            $inter->update([
               'sort_string'=>$string
            ]);

            $this->info('INTERVIEWEE: '.$inter->name .' '.  $inter->surname . ' string:'. $string);

        }


        foreach(Record::all() as $k=>$rec){

            $inter = $rec->interviewees()->first();

            $string = $this->makeNameTransform($inter->surname).$this->makeNameTransform($inter->name);

            $rec->update([
                'sort_string'=>$string
            ]);

            $this->info('RECORD: '.$rec->title .' string:'. $string);

        }

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
