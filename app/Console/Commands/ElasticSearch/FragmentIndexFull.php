<?php

namespace App\Console\Commands\ElasticSearch;

use App\Entities\Record;
use Illuminate\Console\Command;
use Elasticsearch\Client;
use CSD\Image\Image as ImageCSD;
use App\Entities\Fragment;
use App\Entities\Tag;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class FragmentIndexFull extends Command
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
    protected $signature = 'els:fragments';

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

        $count_frgs = $frgs->count();

        foreach($frgs as $f){

//            if($f->record()->first()->status==1) {

                if($f->record()->first()->interviewees()->count()>0) {

                    $this->elasticsearch->index([
                        'index' => 'nagrania',
                        'type' => 'fragmenty',
                        'id' => $f->id,
                        'body' => $this->prepareBodyIndex($f)
                    ]);


                    $this->info('Fragment: ' . $count_frgs . ' ID: ' . $f->id . ' - status: indexowany');

                }else{
                    $this->info('Fragment: ' . $count_frgs.' ID: '.$f->id.' - status: indexowany');
                }

//            }else{
//
//
//
//            }

            $count_frgs--;


        }
    }


    private function prepareBodyIndex($f){

        $array = [];

        $r = $f->record()->first();
        $ex_title = explode(" ",$r->title);

        $interviewee = $r->interviewees()->first();

        $array['fid'] = $f->id;
        $array['record_id'] = $r->id;
        $array['record_title'] = $r->title;
        $array['last_name'] = end($ex_title);
        $array['last_name_transform'] = $this->makeNameTransform($interviewee->surname).$this->makeNameTransform($interviewee->name);
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
            array_push($array['places'],[
                'id'=>$tag->id,
                'name'=>$tag->name
            ]);
        }

        $array['images'] = [];

        return $array;

        $rec = $f->record()->first();
        $interviewee = $rec->interviewees()->first();
        $this->info($interviewee->name.' '.$interviewee->surname);

        $images = Storage::disk('photos')->files();

        foreach($images as $key=>$pic){

            $imgobj = ImageCSD::fromFile(storage_path().'/app/photos/' . $pic);
//            $tags = $imgobj->getAggregate()->getKeywords();
//
//            if(is_array($tags) && count($tags)>0){
//
//                if($tags[0]==$interviewee->name.' '.$interviewee->surname){
//
//                    $size = getimagesize(storage_path().'/app/photos/'.$pic);
//                    array_push($array['images'],[
//                        'image_name'=>$pic,
//                        'image_link'=>'/photos/'.$pic,
//                        'image_size'=>$size[0].'x'.$size[1],
//                        'tags'=>implode(' ',$tags),
//                        'description'=>$imgobj->getAggregate()->getCaption(),
//                        'all'=>$this->getAllDataCaption($imgobj)
//                    ]);
//                }
//
//            }

            $author = $imgobj->getAggregate()->getPhotographerName();

            if(!is_null($author)){

                if($author==$interviewee->name.' '.$interviewee->surname){

                    $tags = $imgobj->getAggregate()->getKeywords();

                    if(is_array($tags) && count($tags)>0){
                        $has_tags = true;
                    }else{
                        $has_tags = false;
                    }

                    $size = getimagesize(storage_path().'/app/photos/'.$pic);
                    array_push($array['images'],[
                        'image_name'=>$pic,
                        'image_link'=>'/photos/'.$pic,
                        'image_size'=>$size[0].'x'.$size[1],
                        'tags'=>($has_tags)?implode(' ',$tags):'',
                        'description'=>$imgobj->getAggregate()->getCaption(),
                        'all'=>$this->getAllDataCaption($imgobj)
                    ]);
                }

            }

        }

        return $array;
    }


    private function getAllDataCaption($imgobj){

        $string = $imgobj->getAggregate()->getCaption();

        $tags = $imgobj->getAggregate()->getKeywords();
        if(is_array($tags) && count($tags)>0){
            $string .= ' '.implode(' ',$tags);
        }

        return $string;
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
