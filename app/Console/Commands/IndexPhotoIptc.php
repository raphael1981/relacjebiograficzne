<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CSD\Image\Image as ImageCSD;
use Illuminate\Support\Facades\Storage;
use App\Entities\Picture;
use App\Repositories;


class IndexPhotoIptc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photo:index {--action=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make index in pictures gallery';

    private $picture;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Repositories\PictureRepositoryEloquent $picture)
    {
        parent::__construct();
        $this->picture = $picture;
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

        $images = Storage::disk('photos')->files();

        if($action=='reindex' || $action=='complete') {

            foreach ($images as $key => $value) {

                $imgobj = ImageCSD::fromFile(storage_path().'/app/photos/' . $value);

//                $this->info($imgobj->getAggregate()->getCaption());
//                $this->info(print_r($imgobj->getAggregate()->getKeywords(),1));

                if($action=='reindex'){

                    if ($this->picture->findWhere(['source' => $value, 'disk' => 'photos'])->count() == 0) {

                        if($imgobj->getAggregate()->getCaption()!='') {

                            $this->picture->create([
                                'source' => $value,
                                'disk' => 'photos',
                                'description'=>$imgobj->getAggregate()->getCaption(),
                                'keywords'=>(is_array($imgobj->getAggregate()->getKeywords()))?implode(',',$imgobj->getAggregate()->getKeywords()):''
                            ]);

                        }

                    } else {

                        if($imgobj->getAggregate()->getCaption()!='') {

                            $imgbase = $this->picture->findWhere(['source' => $value, 'disk' => 'photos'])->first();

                            $this->picture->update([
                                'source' => $value,
                                'disk' => 'photos',
                                'description'=>$imgobj->getAggregate()->getCaption(),
                                'keywords'=>(is_array($imgobj->getAggregate()->getKeywords()))?implode(',',$imgobj->getAggregate()->getKeywords()):''
                            ],$imgbase->id);

                        }

                    }

                }

                if($action=='complete'){

                    if ($this->picture->findWhere(['source' => $value, 'disk' => 'photos'])->count() == 0) {

                        if($imgobj->getAggregate()->getCaption()!='') {

                            $this->picture->create([
                                'source' => $value,
                                'disk' => 'photos',
                                'description'=>$imgobj->getAggregate()->getCaption(),
                                'keywords'=>(is_array($imgobj->getAggregate()->getKeywords()))?implode(',',$imgobj->getAggregate()->getKeywords()):''
                            ]);

                        }

                    }

                }


            }

        }else{
            $this->info('uzyj flagi --action= z opcjami "reindex" nadpisanie indexu / "complete" uzupełnienie indexu');
        }


    }
}
