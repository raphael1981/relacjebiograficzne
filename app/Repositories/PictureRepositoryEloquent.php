<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PictureRepository;
use App\Entities\Picture;
use App\Validators\PictureValidator;

/**
 * Class PictureRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PictureRepositoryEloquent extends BaseRepository implements PictureRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Picture::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function getImagesByCriteria($data){

        $array = [];

        $sql = DB::table('pictures')
            ->select(
                'pictures.id',
                'pictures.source',
                'pictures.description',
                'pictures.keywords',
                'pictures.disk',
                DB::raw('round(
                    (
                        length(`pictures`.`description`) -
                        length(replace(`pictures`.`description`, "'.$data['frase'].'", ""))
                    )
                    /
                    length("'.$data['frase'].'")
                    +
                    round(
                        length(`pictures`.`keywords`) -
                        length(replace(`pictures`.`keywords`, "'.$data['frase'].'", ""))
                    )/length("'.$data['frase'].'")
                    ) as weight')
            )
            ->where('pictures.description', 'RLIKE', ''.$data['frase'])
            ->orWhere('pictures.keywords', 'RLIKE', ''.$data['frase'])
            ->orderByRaw("pictures.description COLLATE utf8_bin ASC")
            ->orderBy('weight', 'desc');


        $images = $sql->get();

        foreach($images as $key=>$image){

            $array[$key] = new \stdClass();
            $array[$key]->src = '/image/'.$image->source.'/'.$image->disk;
            $array[$key]->safeSrc = '/image/'.$image->source.'/'.$image->disk;
            $array[$key]->thumb = '/image/'.$image->source.'/'.$image->disk;
            $array[$key]->caption = $image->description;

            $imagesize = getimagesize(storage_path().'/app/'.$image->disk.'/'.$image->source);

            $array[$key]->size = $imagesize[0].'x'.$imagesize[1];
            $array[$key]->type = 'image';

        }

        return \GuzzleHttp\json_encode($array);

    }

}
