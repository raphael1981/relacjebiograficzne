<?php

namespace App\Http\Controllers;

use App\Entities\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use CSD\Image\Image as ImageCSD;

class ImageController extends Controller
{

    public function getImage($filename,$disk,$basesize=null){
        ini_set('memory_limit','2048M');
        ini_set('max_execution_time', 12000);

        $ctype = Storage::disk($disk)->mimeType($filename);

        if(!is_null($basesize)) {

            $img = Image::make(storage_path() . '/app/' . $disk . '/' . $filename)
                ->resize($basesize, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
        }else {
            $img = Image::make(storage_path() . '/app/' . $disk . '/' . $filename);
        }
            return $img->response($ctype)
                ->header('Content-Type', $ctype);

    }


    public function getImageFront($filename,$disk,$basesize=null,$crop=false,$width=null,$height=null,$left=null,$top=null){
        ini_set('memory_limit','2048M');
        ini_set('max_execution_time', 12000);

        $ctype = Storage::disk($disk)->mimeType($filename);


        if(!is_null($basesize) && $crop!='true') {

            $img = Image::make(storage_path().'/app/'.$disk.'/'.$filename)
                ->resize(null, $basesize, function ($constraint) {
                    $constraint->aspectRatio();
                });

            return $img->response($ctype)
                ->header('Content-Type',  $ctype);



        }elseif($crop=='true' && !is_null($width) && !is_null($height) && $width!='null' && $height!='null'){

            $img = Image::make(storage_path().'/app/'.$disk.'/'.$filename)
                ->crop($width, $height, $left, $top);

            return $img->response($ctype)
                ->header('Content-Type',  $ctype);


        }else{

		  /*
            $img = Storage::disk($disk)->get($filename);
            return response($img, 200)
                ->header('Content-Type', $ctype);
         */
		    $img = Storage::disk($disk)->get($filename);
            list($w, $h) = getimagesize(storage_path().'/app/'.$disk.'/'.$filename); 
            if($w>1200 || $h>1200){
				$watermark = 'images/znak_wodny5.png';
			}else{
				$watermark = 'images/znak_wodny4.png';
			}

			$img = Image::make(storage_path().'/app/'.$disk.'/'.$filename)		
			 ->insert($watermark,'bottom-left');				

			
			return $img->response($ctype)
                ->header('Content-Type',  $ctype);
        }





    }
	
	public function getImageFilterSize($filename, $disk, $basesize=null, $filter=null){

        ini_set('memory_limit','2048M');
        ini_set('max_execution_time', 12000);
		$ctype = Storage::disk($disk)->mimeType($filename);
		
		$img = Image::make(storage_path().'/app/'.$disk.'/'.$filename)
                ->resize(null, $basesize, function ($constraint) {
                    $constraint->aspectRatio();
                });

        $img->greyscale();
		$img->colorize(13, 13, 13);
		
		return $img->response($ctype)
                ->header('Content-Type',  $ctype);
		
	}


    public function getIntervieweeImage($disk, $id){

        ini_set('memory_limit','2048M');
        ini_set('max_execution_time', 12000);
        $path = Storage::disk($disk)
            ->getDriver()
            ->getAdapter()
            ->getPathPrefix();

        $files = Storage::disk($disk)->files();

        $interviewees = Record::find($id)->interviewees()->get();


        $array = [];


        foreach($interviewees as $key=>$inter){

            $array[$key] = [];

            foreach($files as $k=>$img){

                $imgobj = ImageCSD::fromFile($path . $img);
                $str_key_name = $inter->name.' '.$inter->surname;

                if($str_key_name==$imgobj->getAggregate()->getPhotographerName()){

                    $size = getimagesize($path.$img);

                    $array[$key][$k] = new \stdClass();
                    $array[$key][$k]->src = url('/image/'.$img.'/'.$disk);
                    $array[$key][$k]->safeSrc = url('/image/'.$img.'/'.$disk.'/150');
                    $array[$key][$k]->thumb = url('/image/'.$img.'/'.$disk.'/150');
                    $array[$key][$k]->caption = $imgobj->getAggregate()->getCaption();
                    $array[$key][$k]->urgency = $imgobj->getAggregate()->getUrgency();
                    $array[$key][$k]->size = $size[0].'x'.$size[1];
                    $array[$key][$k]->sizeArray = $size;
                    $array[$key][$k]->type = 'image';
                    $array[$key][$k]->orientation = ($size[0]>$size[1])?'landscape':'portrait';
//                    $array[$key][$k]->urgnecy = $imgobj->getAggregate()->getUrgency();
                }


            }

            $array[$key] = array_values($array[$key]);

        }



//        src: AppService.url +'/image/'+galleries[key].pictures[i].source+'/'+galleries[key].pictures[i].disk,
//                    safeSrc: AppService.url +'/image/'+galleries[key].pictures[i].source+'/'+galleries[key].pictures[i].disk,
//                    thumb: AppService.url +'/image/'+galleries[key].pictures[i].source+'/'+galleries[key].pictures[i].disk+'/150',
//                    caption: 'Lorem Ipsum Dolor',
//                    size: galleries[key].pictures[i].size[0]+'x'+galleries[key].pictures[i].size[1],
//                    type: 'image'



        return $array;

    }


}
