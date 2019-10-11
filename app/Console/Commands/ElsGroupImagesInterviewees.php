<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CSD\Image\Image as ImageCSD;
use Elasticsearch\Client;
use App\Entities\Fragment;
use App\Entities\Tag;
use App\Entities\Interviewee;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

class ElsGroupImagesInterviewees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'els:group:images:interviewee';

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

        foreach($images as $key=>$pic){

            $imgobj = ImageCSD::fromFile(storage_path().'/app/photos/' . $pic);
            $tags = $imgobj->getAggregate()->getKeywords();

            if(is_array($tags) && count($tags)>0){
                $this->info($tags[0]);
            }

        }
    }
}
