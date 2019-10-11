<?php

namespace App\Console\Commands\ElasticSearch\Panel;

use App\Entities\MonitorIndx;
use App\Entities\Record;
use Carbon\Carbon;
use Illuminate\Console\Command;
use CSD\Image\Image as ImageCSD;
use Elasticsearch\Client;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class ImagesIndex extends Command
{

    private $elasticsearch;
    private $curl;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'els:panel:images {id}';

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
        $monitor = MonitorIndx::find($this->argument('id'));

        $this->deleteIndex('images');

        $monitor = $this->makeImagesIndex($monitor);

        $monitor->end_at = Carbon::now();
        $monitor->status = 1;
        $monitor->save();

    }

    private function deleteIndex($name){
        $response = Curl::to('http://localhost:9200/'.$name)->delete();
    }


   private function makeImagesIndex($monitor)
    {
        $map = Storage::disk('es_prop')->get('image_to_record_index.json','Content');

        $type_map = str_replace('<name_of_type>', 'iptc_images', $map);
        $response = Curl::to('http://localhost:9200/images')
            ->withData( json_decode($type_map,true) )
            ->asJson()
            ->put();

        $images = Storage::disk('photos')->files();

        $monitor->start_at = Carbon::now();
        $monitor->save();

        foreach($images as $key=>$pic){

            $imgobj = ImageCSD::fromFile(storage_path().'/app/photos/' . $pic);


            $this->info($pic);

            if(!is_null($imgobj->getAggregate()->getKeywords())){
                $tags = implode(' ',$imgobj->getAggregate()->getKeywords());
            }else{
                $tags = '';
            }

            if(!is_null($imgobj->getAggregate()->getCaption())){
                $desc = ' '.$imgobj->getAggregate()->getCaption();
            }else{
                $desc = '';
            }

            $size = getimagesize(storage_path().'/app/photos/'.$pic);

            $this->elasticsearch->index([
                'index' => 'images',
                'type' => 'iptc_images',
                'id' => $key+1,
                'body' => [
                    "image_name"=>$pic,
                    "image_link"=>'/photos/'.$pic,
                    "image_link_generate"=>'/image/'.$pic.'/photos',
                    'image_size'=>$size[0].'x'.$size[1],
                    'record_id'=>$this->getRecordId($imgobj)->id,
                    'record_status'=>$this->getRecordId($imgobj)->status,
                    'tags'=>$tags,
                    'description'=>$desc,
                    "all"=>$tags." ".$desc
                ]
            ]);

        }

        return $monitor;
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

}
