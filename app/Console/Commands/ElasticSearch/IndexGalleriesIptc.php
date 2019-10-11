<?php

namespace App\Console\Commands\ElasticSearch;

use App\Entities\Gallery;
use App\Entities\Record;
use Illuminate\Console\Command;
use CSD\Image\Image as ImageCSD;
use Elasticsearch\Client;
use App\Entities\Fragment;
use App\Entities\Tag;
use App\Entities\Interviewee;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class IndexGalleriesIptc extends Command
{

    private $elasticsearch;
    private $curl;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'els:gallery:iptc';

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

        $map = Storage::disk('es_prop')->get('gallery_iptc_index.json','Content');

        $response = Curl::to('http://localhost:9200/galleries')
            ->withData( json_decode($map,true) )
            ->asJson()
            ->put();

        foreach(Gallery::where('mode','iptccategory')->get() as $g){

            $path = Storage::disk($g->disk)
                ->getDriver()
                ->getAdapter()
                ->getPathPrefix();


            $files = Storage::disk($g->disk)->files();

            $images = [];

            foreach($files as $f){

                $imgobj = ImageCSD::fromFile($path . $f);

                $sup_category = $imgobj->getAggregate()->getSupplementalCategories();

                if(is_array($sup_category)) {

                    $sup_cats = $this->trimArrayStrings($sup_category);

                    foreach ($sup_cats as $k => $scat) {

                        if(trim($scat)==strtolower($g->name)) {


                            $size = getimagesize(storage_path() . '/app/' . $g->disk . '/' . $f);

//                            $this->info($g->name.':'.$f.print_r($size));
                            if ($this->getRecordId($imgobj)->status != null) {
                                array_push($images, [
                                    'img' => $f,
                                    'img_link' => '/photos/' . $f,
                                    'img_link_generate' => '/image/' . $f . '/photos',
                                    'desc' => $imgobj->getAggregate()->getCaption(),
                                    'size' => $size[0] . 'x' . $size[1],
                                    'orientation' => ($size[0] > $size[1]) ? 'landscape' : 'portrait',
                                    'record_id' => $this->getRecordId($imgobj)->id,
                                    'record_status' => $this->getRecordId($imgobj)->status
                                ]);
                            }
                        }
                    }

                }

            }

            $this->elasticsearch->index([
                'index' => 'galleries',
                'type' => 'gallery',
                'id' => $g->id,
                'body' => [
                    "id"=>$g->id,
                    "name"=>$g->name,
                    'images'=>$images
                ]
            ]);

            $this->info('name:'.$g->name.' id:'.$g->id.' count_images:'.count($images));

        }
    }

    private function getRecordId($imgobj){

        $r = new \stdClass();
        $r->id = null;
        $r->status = null;
        foreach(Record::all() as $k=>$rec) {
            $inv = $rec->interviewees()->first();

            $author = $imgobj->getAggregate()->getPhotographerName();

            if (!is_null($author) && count($inv)>0) {

                if($author==$inv->name.' '.$inv->surname){
                    $r->id = $rec->id;
                    $r->status = $rec->status;
                }
            }
        }

        return $r;

    }


    private function trimArrayStrings($scats){

        $array = [];

        foreach($scats as $k=>$s){

            array_push($array, strtolower(trim($s)));

        }

        return $array;

    }

}
