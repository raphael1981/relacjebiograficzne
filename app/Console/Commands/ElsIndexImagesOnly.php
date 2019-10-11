<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CSD\Image\Image as ImageCSD;
use Elasticsearch\Client;
use App\Entities\Fragment;
use App\Entities\Tag;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class ElsIndexImagesOnly extends Command
{

    private $elasticsearch;
    private $curl;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'els:images:only';

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
        $map = Storage::disk('es_prop')->get('image_full_desc_mapping.json','Content');

        $type_map = str_replace('<name_of_type>', 'iptc_images', $map);
        $response = Curl::to('http://localhost:9200/images')
            ->withData( json_decode($type_map,true) )
            ->asJson()
            ->put();

        $images = Storage::disk('photos')->files();

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
                    'image_size'=>$size[0].'x'.$size[1],
                    'tags'=>$tags,
                    'description'=>$desc,
                    "all"=>$tags." ".$desc,
                    "fragments"=>[]
                ]
            ]);

        }

        $this->call('els:associate:images:fragments', []);

    }
}
