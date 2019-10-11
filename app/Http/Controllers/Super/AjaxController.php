<?php

namespace App\Http\Controllers\Super;

use App\Entities\Interviewee;
use App\Entities\Place;
use App\Entities\Record;
use App\Entities\Redactor;
use App\Entities\Tag;
use App\Helpers\XMLHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as  InterImage;
use Illuminate\Database\Eloquent\Model;


class AjaxController extends Controller
{

    public function getMediaImages($srctype, $disk=null){

        $files = Storage::disk($disk)->files();

        $array = [];


        foreach($files as $key=>$img){

            $array[$key] = new \stdClass();

            $array[$key]->disk = $disk;

            $array[$key]->image = '/image/'.$img.'/'.$disk;

            $array[$key]->imagename = $img;

            $array[$key]->rootsize = getimagesize(storage_path().'/app/'.$disk.'/'.$img);


        }


        return $array;

    }



    public function uploadImageTo($disk, Request $request){


        $std = new \stdClass();

        $image = $request->file('file');

        $temp = explode('.',$image->getClientOriginalName());
        $ext = $temp[count($temp)-1];
        $newfilename = (microtime(true)*10000).'.'.strtolower($ext);

        Storage::disk($disk)->put($newfilename, File::get($image->getRealPath()));

        $std->disk = $disk;
        $std->image = '/image/'.$newfilename.'/'.$disk;
        $std->imagename = $newfilename;
        $std->rootsize = getimagesize(storage_path().'/app/'.$disk.'/'.$newfilename);

        return json_encode($std);


    }



    public function getImageByData(Request $request){

        $std =  new \stdClass();


        $std->disk = $request->get('disk');
        $std->image = '/image/'.$request->get('intro_image').'/'.$request->get('disk');
        $std->imagename = $request->get('intro_image');
        $std->rootsize = getimagesize(storage_path().'/app/'.$request->get('disk').'/'.$request->get('intro_image'));


        return json_encode($std);
    }



    public function getTagsByQuery(Request $request){

        return Tag::where('name','LIKE', '%'.$request->get('guery').'%')->get();

    }

    public function getPlacesByQuery(Request $request){

        return Place::where('name','LIKE', '%'.$request->get('guery').'%')->get();

    }


    public function getAllTags(){
        return Tag::orderBy('name','asc')->get();
    }

    public function getAllPlaces(){
        return Place::all();
    }


    public function getMediaSources(Request $request){

        $std = new \stdClass();

        $std->audio = File::files($request->get('audio'));
        $std->video = File::files($request->get('video'));

        for($i=0;$i<count($std->audio);$i++){

            $std->audio[$i] = [
                'filename' => str_replace($request->get('audio').'/', '', (string)$std->audio[$i]),
                'filepath' => (string)$std->audio[$i]
            ];


        }

        for($i=0;$i<count($std->video);$i++){

            $std->video[$i] = [
                'filename' => str_replace($request->get('video').'/', '', (string)$std->video[$i]),
                'filepath' => (string)$std->video[$i]
            ];

        }

        // $std->audio = \GuzzleHttp\json_decode(file_get_contents("http://wystawiacz.dsh2.usermd.net/audio"));
        // $std->video = \GuzzleHttp\json_decode(file_get_contents("http://wystawiacz.dsh2.usermd.net/video"));

        return json_encode($std);

    }


    public function getAllInterviewees(){

        return Interviewee::all();

    }


    public function uploadRecordXml(Request $request){

        $std = new \stdClass();

        $file = $request->file('file');

        $filename  = str_slug(str_replace('.xml', '', $file->getClientOriginalName()), '-') .'-'. time() .'.'. $file->getClientOriginalExtension();

        $file->move(base_path('public/xml'), $filename);

        $xml = XMLHelper::readTranscriptionXML('xml/'.$filename);

        $std->xmlurl = url('xml/'.$filename);
        $std->xmldata = $xml;
        $std->filename = $filename;
        $std->filepath = base_path('public/xml').'/'.$filename;

        return json_encode($std);

    }



    public function removeRecordXml(Request $request){

        File::delete($request->get('filepath'));
        return response('{"success":true}', 200, ['Content-type'=>'application/json']);

    }



    public function uploadImageToDisk($disk, Request $request){

        $blob = $request->get('file');
        $blobRE = '/^data:((\w+)\/(\w+));base64,(.*)$/';
        if (preg_match($blobRE, $blob, $m))
        {
            Storage::disk($disk)->put($request->get('fname'), base64_decode($m[4]));
            return response('{"success":true,"data":'.json_encode($request->all()).'}', 200, ['Content-type'=>'application/json']);
        }

        return response('{"success":false}', 200, ['Content-type'=>'application/json']);

    }


    public function removeImageFromDisk($disk, Request $request){

        Storage::disk($disk)->delete($request->get('fname'));

        return response('{"success":true}', 200, ['Content-type'=>'application/json']);
    }



    public function getAllRecords(){
        return Record::all();
    }

    public function getAllRedactors(){
        return Redactor::all();
    }

}
