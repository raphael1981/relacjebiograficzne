<?php

namespace App\Http\Controllers\Customer;

use App\Entities\Interviewee;
use App\Entities\Place;
use App\Entities\Record;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use Ixudra\Curl\Facades\Curl;
use Elasticsearch\Client;

class AjaxController extends Controller
{

    private $article;
    private $record;
    private $interviewee;
    private $gallery;
    private $picture;
    private $thread;

    public function __construct(
        Repositories\ArticleRepositoryEloquent $article,
        Repositories\RecordRepositoryEloquent $record,
        Repositories\IntervieweeRepositoryEloquent $interviewee,
        Repositories\GalleryRepositoryEloquent $gallery,
        Repositories\PictureRepositoryEloquent $picture,
        Repositories\ThreadRepositoryEloquent $thread
    )
    {
        $this->article = $article;
        $this->record = $record;
        $this->interviewee = $interviewee;
        $this->gallery = $gallery;
        $this->picture = $picture;
        $this->thread = $thread;
    }

    public function getArticles($catid, $start, $limit){

        return json_encode($this->article->getAriclesDataMainMark($catid, $start, $limit));

    }


    public function getLastFrontRecords($start, $limit){

        return json_encode($this->record->getLastRecords($start,$limit));

    }

    public function getArticleGalleryData($aid){

        return json_encode($this->article->getArticleFullData($aid));

    }

    public function getIntevieweesIndexData(Request $request){

        return json_encode($this->interviewee->getIntervieweesByIndex($request->all()));

    }

    public function getAllInterviewees(){

        return json_encode($this->interviewee->getAllInterviewees());
//      return json_encode($this->interviewee->orderBy('surname')->findWhere(['status'=>1]));
    }


    public function getGalleries($start, $limit){

        return json_encode($this->gallery->getGalleriesData($start,$limit));

    }


    public function getFullGallery($id){

        return json_encode($this->gallery->getGalleryFullData($id));

    }



    public function getGallery($id, $start, $limit){

        return json_encode($this->gallery->getGalleryData($id, $start,$limit));

    }


    public function getGalleryIptc($id, $mode){

        $images = Curl::to('http://localhost:9200/galleries/gallery/'.$id)->get();

        return $images;

//        return json_encode($this->gallery->getGalleryDataIptc($id, $mode));

    }


    public function getAllThreads(){

        return \GuzzleHttp\json_encode($this->thread->getThredsAndRecordsCount());

    }

    public function getThreadRecords($id){

        $array = [];

//        ->orderByRaw("surname COLLATE utf8_bin ASC")
//            ->orderByRaw("name COLLATE utf8_bin ASC")
//            ->orderBy('sort_string')

        foreach($this->thread->find($id)->records()->orderByRaw('sort_string COLLATE utf8_bin ASC')->get() as $key=>$tr){
            $tr->interviewee = $tr->interviewees()->first();
            array_push($array,$tr);
        }

        return \GuzzleHttp\json_encode($array);

    }


    public function getLinkedRecords($id){

        $array = [];

        $records = $this->record->find($id)->recordsMorphedByMany()->get();

        foreach($records as $key=>$record){

            $array[$key] = new \stdClass();

            $array[$key]->record = $record;
            $array[$key]->interviewee = $this->record->find($record->id)->interviewees()->first();

        }

        return json_encode($array);
    }


    public function getSearchImages(Request $request){

        return $this->picture->getImagesByCriteria($request->all());
    }


    public function getAllPlaces(){
        return Place::all();
    }

}
