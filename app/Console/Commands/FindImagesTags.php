<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CSD\Image\Image as ImageCSD;
use Illuminate\Support\Facades\Storage;

class FindImagesTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:images:tags';

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
        $images = Storage::disk('photos')->files();
        $array = [];

        foreach($images as $key=>$pic) {

            $imgobj = ImageCSD::fromFile(storage_path() . '/app/photos/' . $pic);
//            $this->info(print_r($imgobj->getAggregate()->getKeywords()));

            if(is_array($imgobj->getAggregate()->getKeywords())) {

                $tags = [];
                foreach ($imgobj->getAggregate()->getKeywords() as $tag) {
                    array_push($tags, [
                        "tag" => "$tag"
                    ]);
                }

                $array = array_merge($array, $tags);

            }

        }

        $collection = collect($array);
        $unique = $collection->unique('tag');

//        $this->info(print_r($unique->values()->all()));

        file_put_contents(public_path().'/json/images_tags.json',json_encode($unique->values()->all()));

    }
}
