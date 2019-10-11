<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\GalleryRepository;
use App\Entities\Gallery;
use App\Validators\GalleryValidator;
use Ixudra\Curl\Facades\Curl;
use Elasticsearch\Client;
use CSD\Image\Image as ImageCSD;


/**
 * Class GalleryRepositoryEloquent
 * @package namespace App\Repositories;
 */
class GalleryRepositoryEloquent extends BaseRepository implements GalleryRepository
{

    private $elasticsearch;
    private $curl;
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Gallery::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function getGalleriesData($start,$limit){

//        if(Cache::has('galerie')){
//            return Cache::get('galerie');
//        }

        $array = [];

        $galleries = Gallery::skip($start)
            ->take($limit)
            ->orderBy('name', 'desc')
            ->where('status','=',1)
            ->where(function($q){
                $q->orWhere('destination','gallery');
                $q->orWhere('destination','both');
            })
            ->get();

        foreach($galleries as $k=>$gal){


            $array[$k] = new \stdClass();
            $array[$k]->gallery = $gal;
            $array[$k]->first_picture = new \stdClass();
            $array[$k]->first_picture->source = $this->getFirstImage($gal->mode, $gal->disk, $gal->name, $gal->alias, $gal->id);
//            $array[$k]->first_picture->disk = $gal->disk;
//            $size = getimagesize(storage_path().'/app/'.$gal->disk.'/'.$array[$k]->first_picture->source);
            $array[$k]->first_picture->disk = 'primaryphoto';
            $size = getimagesize(storage_path().'/app/primaryphoto/'.$array[$k]->first_picture->source);
            $array[$k]->orientation = ($size[0]>$size[1])?'landscape':'portrait';


            $array[$k]->link = '/galeria/'.$array[$k]->gallery->id.'-'.str_slug($array[$k]->gallery->name);

        }

//        Cache::forever('galerie', $array);
        return $array;

    }



    private function getFirstImage($mode, $disk, $name, $alias, $id){

//        $array = [];
        $image = null;


        switch($mode){

            case 'database':


                $files = Storage::disk('primaryphoto')->files();

                foreach($files as $key=>$file){

                    $slugimagename = $id.'-'.$alias;
                    $array_from_name = explode('.', $file);
                    array_pop($array_from_name);

                    $only_name = '';

                    foreach($array_from_name as $string_el){
                        $only_name .= $string_el;
                    }

                    if($only_name==$slugimagename){
                        $image =  $file;
                    }


                }



                break;

            case 'iptcauthor':


                $files = Storage::disk('primaryphoto')->files();

                foreach($files as $key=>$file){

                    $slugimagename = $id.'-'.$alias;
                    $array_from_name = explode('.', $file);
                    array_pop($array_from_name);

                    $only_name = '';

                    foreach($array_from_name as $string_el){
                        $only_name .= $string_el;
                    }

                    if($only_name==$slugimagename){
                        $image =  $file;
                    }


                }


                break;

            case 'iptccategory':


                $files = Storage::disk('primaryphoto')->files();

                foreach($files as $key=>$file){

                    $slugimagename = $id.'-'.$alias;
                    $array_from_name = explode('.', $file);
                    array_pop($array_from_name);

                    $only_name = '';

                    foreach($array_from_name as $string_el){
                        $only_name .= $string_el;
                    }

                    if($only_name==$slugimagename){
                        $image =  $file;
                    }


                }


//                $path = Storage::disk($disk)
//                    ->getDriver()
//                    ->getAdapter()
//                    ->getPathPrefix();
//
//                $files = Storage::disk($disk)->files();
//
//                foreach($files as $key=>$file){
//
//                    $imgobj = ImageCSD::fromFile($path . $file);
//
//                    $sup_category = $imgobj->getAggregate()->getSupplementalCategories();
//
//
//                    if(!is_null($sup_category)) {
//
//
//                        $sup_cats = $this->trimArrayStrings($sup_category);
//
//                        array_push($array, $sup_cats);
//
//                        foreach($sup_cats as $k=>$scat){
//
//
//                            if (trim($scat) == strtolower($name)) {
//
//                                if(!is_null($file)){
//
//                                    $image = $file;
//                                    break;
//
//                                }
//
//                            }
//
//                        }
//
//
//                    }
//
//
//                }

                break;


        }

        return $image;


    }


    private function trimArrayStrings($scats){

        $array = [];

        foreach($scats as $k=>$s){

            array_push($array, strtolower(trim($s)));

        }

        return $array;

    }




    public function getGalleryData($id, $start, $limit){

        $array = [];

        $gal = Gallery::find($id);


        $gallery = Gallery::find($id)->pictures()->skip($start)->take($limit)->get();

        foreach($gallery as $key=>$gal){

            $path = Storage::disk($gal->disk)
                ->getDriver()
                ->getAdapter()
                ->getPathPrefix();

            $imgobj = ImageCSD::fromFile($path . $gal->source);

            $imgsize = getimagesize(storage_path().'/app/'.$gal->disk.'/'.$gal->source);

            $array[$key] = $gal;
            $array[$key]->size = $imgsize;
            $array[$key]->caption = $imgobj->getAggregate()->getCaption();
            $array[$key]->orientation = ($imgsize[0]>$imgsize[1])?'landscape':'portrait';

        }

        return $array;

    }


    public function getGalleryFullData($id){

        $array = [];

        $gallery = Gallery::find($id)->pictures()->get();

        foreach($gallery as $key=>$gal){

            $path = Storage::disk($gal->disk)
                ->getDriver()
                ->getAdapter()
                ->getPathPrefix();

            $imgobj = ImageCSD::fromFile($path . $gal->source);

            $imgsize = getimagesize(storage_path().'/app/'.$gal->disk.'/'.$gal->source);

            $array[$key] = $gal;
            $array[$key]->size = $imgsize;
            $array[$key]->caption = $imgobj->getAggregate()->getCaption();
            $array[$key]->orientation = ($imgsize[0]>$imgsize[1])?'landscape':'portrait';

        }

        return $array;

    }


    public function getGalleryDataIptc($id, $mode){

        $gallery = Gallery::find($id);


        $path = Storage::disk($gallery->disk)
            ->getDriver()
            ->getAdapter()
            ->getPathPrefix();


        $files = Storage::disk($gallery->disk)->files();


        $images = [];


//        $i=0;

        foreach($files as $key=>$file){


            if($mode=='iptccategory'){

                $imgobj = ImageCSD::fromFile($path . $file);
                $sup_category = $imgobj->getAggregate()->getSupplementalCategories();

                if(!is_null($sup_category)) {


                    $sup_cats = $this->trimArrayStrings($sup_category);

                    foreach($sup_cats as $k=>$scat){


                        if (trim($scat) == strtolower($gallery->name)) {

                            if(!is_null($file)){

                                $to_push = new \stdClass();
                                $to_push->gallery = $gallery;
                                $to_push->source = $file;
                                $to_push->size = getimagesize(storage_path().'/app/'.$gallery->disk.'/'.$file);
                                $to_push->caption = $imgobj->getAggregate()->getCaption();
                                $to_push->orientation = ($to_push->size[0]>$to_push->size[1])?'landscape':'portrait';


                                array_push($images, $to_push);

                            }

                        }

                    }


                }






            }


            if($mode=='iptcauthor'){



            }



            if($mode=='database'){



            }


        }


        return $images;
    }

}
