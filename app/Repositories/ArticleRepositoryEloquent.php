<?php

namespace App\Repositories;

use App\Entities\Category;
use App\Entities\Gallery;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ArticleRepository;
use App\Entities\Article;
use App\Validators\ArticleValidator;

/**
 * Class ArticleRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ArticleRepositoryEloquent extends BaseRepository implements ArticleRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Article::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function searchByCriteria($data){


        $query = Article::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['title']) {
                    $q->orWhere('title', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['intro']) {
                    $q->orWhere('intro', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['content']) {
                    $q->orWhere('content', 'LIKE', '%' . $data['frase'] . '%');
                }

            }



        });

        if(isset($data['filter']['category'])){

            if($data['filter']['category']['id']!=0){
                $query->where('category_id',$data['filter']['category']['id'] );
            }

        }


        $elements = $query
                    ->skip($data['start'])
                    ->take($data['limit'])
                    ->get();

        $elements = $this->refactorSearchElements($elements);

        $cquery = Article::where(function($q) use ($data){

            if($data['frase']!='') {

                if ($data['searchcolumns']['title']) {
                    $q->orWhere('title', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['intro']) {
                    $q->orWhere('intro', 'LIKE', '%' . $data['frase'] . '%');
                }

                if ($data['searchcolumns']['content']) {
                    $q->orWhere('content', 'LIKE', '%' . $data['frase'] . '%');
                }

            }

            if(isset($data['filter']['category'])){

                if($data['filter']['category']['id']!=0){
                    $q->where('category_id',$data['filter']['category']['id'] );
                }

            }

        });

        if(isset($data['filter']['category'])){

            if($data['filter']['category']['id']!=0){
                $cquery->where('category_id',$data['filter']['category']['id'] );
            }

        }

        $total = $cquery->get();
        $count = count($total);

        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $count;

        return json_encode($std);

    }

    private function refactorSearchElements($elements){

        $array = [];

        foreach($elements as $k=>$el){

            $array[$k] = new \stdClass();
            $array[$k] = $el;
            $array[$k]->category = Category::find($el->category_id);


        }

        return $array;

    }

    public function getAllArticlesData(){
        $array = [];
        $arts = Article::where('status',1)
            ->orderBy('published_at', 'desc')
            ->get();
        foreach($arts as $k=>$art){
            $array[$k] = new \stdClass();
            $array[$k]->artdata = $art;
            if($art->intro_image!=''){
                $array[$k]->image = url('/pic/'.$art->intro_image.'/'.$art->disk);
                $size = getimagesize(storage_path().'/app/'.$art->disk.'/'.$art->intro_image);

                if($size[0]>$size[1]){
                    $difference = $size[0]-$size[1];
                    $array[$k]->orientation = 'landscape';

                }else{
                    $difference = $size[1]-$size[0];
                }

            }else{
                $array[$k]->image = null;
            }

            $array[$k]->category = Category::find($art->category_id);
            $array[$k]->link = $array[$k]->category->id.'-'.$array[$k]->category->alias.'/'.$array[$k]->artdata->id.'-'.$array[$k]->artdata->alias;

        }
        return $array;
    }

    public function getAriclesData($catid, $start, $limit){

        $array = [];

        $arts = Article::where('category_id', $catid)
                ->where('status',1)
                ->skip($start)
                ->take($limit)
                ->orderBy('published_at', 'desc')
                ->get();

        foreach($arts as $k=>$art){

            $array[$k] = new \stdClass();
            $array[$k]->artdata = $art;
            if($art->intro_image!=''){
                $array[$k]->image = url('/image/'.$art->intro_image.'/'.$art->disk);

                $size = getimagesize(storage_path().'/app/'.$art->disk.'/'.$art->intro_image);

                if($size[0]>$size[1]){
                    $difference = $size[0]-$size[1];
                    $array[$k]->orientation = 'landscape';
//                    $percentmore = ($difference/$size[0])*100;
//                    $array[$k]->offset = $percentmore;

//                    if($percentmore<=30){
//                        $array[$k]->offset = 10;
//                    }elseif($percentmore>30 && $percentmore<50){
//                        $array[$k]->offset = 20;
//                    }elseif($percentmore>=50 && $percentmore<70){
//                        $array[$k]->offset = 30;
//                    }else{
//                        $array[$k]->offset = 40;
//                    }

                }else{
                    $difference = $size[1]-$size[0];
//                    $array[$k]->orientation = 'portrait';
//                    $percentmore = ($difference/$size[1])*100;

//                    if($percentmore<=30){
//                        $array[$k]->offset = 10;
//                    }elseif($percentmore>30 && $percentmore<50){
//                        $array[$k]->offset = 20;
//                    }elseif($percentmore>=50 && $percentmore<70){
//                        $array[$k]->offset = 30;
//                    }else{
//                        $array[$k]->offset = 40;
//                    }

                }



            }else{
                $array[$k]->image = null;
//                $array[$k]->orientation = null;
//                $array[$k]->offset = null;
            }

            $array[$k]->category = Category::find($art->category_id);
            $array[$k]->link = $array[$k]->category->id.'-'.$array[$k]->category->alias.'/'.$array[$k]->artdata->id.'-'.$array[$k]->artdata->alias;

        }

        return $array;

    }

    public function getAriclesDataMainMark($catid, $start, $limit){

        $array = [];

        $fart = Article::where('main',1)->first();

        $arts = Article::where('category_id', $catid)
            ->where('status',1)
            ->skip($start)
            ->take($limit)
            ->orderBy('published_at', 'desc')
            ->get();


        if(!is_null($fart) && $start==0){

            $array[0] = new \stdClass();
            $array[0]->artdata = $fart;

            if($fart->intro_image!=''){
                $array[0]->image = url('/image/'.$fart->intro_image.'/'.$fart->disk);

                $size = getimagesize(storage_path().'/app/'.$fart->disk.'/'.$fart->intro_image);

                if($size[0]>$size[1]){
                    $difference = $size[0]-$size[1];
                    $array[0]->orientation = 'landscape';
                }else{
                    $difference = $size[1]-$size[0];
                }


            }else{
                $array[0]->image = null;
            }

            $array[0]->category = Category::find($fart->category_id);
            $array[0]->link = $array[0]->category->id.'-'.$array[0]->category->alias.'/'.$array[0]->artdata->id.'-'.$array[0]->artdata->alias;

        }


        foreach($arts as $k=>$art){

            if(count($fart)>0 && $start==0)
                $k++;

            //////////////////////////////////////////////////////////////////////////////////////////

            $array[$k] = new \stdClass();
            $array[$k]->artdata = $art;

            if($art->intro_image!=''){
                $array[$k]->image = url('/image/'.$art->intro_image.'/'.$art->disk);

                $size = getimagesize(storage_path().'/app/'.$art->disk.'/'.$art->intro_image);

                if($size[0]>$size[1]){
                    $difference = $size[0]-$size[1];
                    $array[$k]->orientation = 'landscape';
                }else{
                    $difference = $size[1]-$size[0];
                }


            }else{
                $array[$k]->image = null;
            }

            $array[$k]->category = Category::find($art->category_id);
            $array[$k]->link = $array[$k]->category->id.'-'.$array[$k]->category->alias.'/'.$array[$k]->artdata->id.'-'.$array[$k]->artdata->alias;

        }

        return $array;

    }

    public function parseSlugGetData($slug){

        $std = new \stdClass();

        $array = explode('-',$slug);
        $id = $array[key($array)];
        $article = $this->find($id);
        $std->article = $article;
        $std->galleries = $article->galleries()->get();
        foreach($std->galleries as $key=>$gal){
            $std->galleries[$key]->pictures = Gallery::find($gal->id)->pictures()->get();
        }

        return $std;

    }


    public function parseSlugGetDataOnlyArticle($slug){

        $array = explode('-',$slug);
        $id = $array[key($array)];

        return  $this->find($id);

    }


    public function getArticleFullData($aid){

        $std = new \stdClass();

        $article = $this->find($aid);
        $std->article = $article;
        $std->galleries = $article->galleries()
            ->where('status',1)
            ->where(function($q){
                $q->orWhere('destination','article');
                $q->orWhere('destination','both');
            })->get();


        foreach($std->galleries as $key=>$gal){
            $std->galleries[$key]->pictures = Gallery::find($gal->id)->pictures()->get();

            foreach($std->galleries[$key]->pictures as $k=>$p){
                $std->galleries[$key]->pictures[$k] = $p;
                $std->galleries[$key]->pictures[$k]->size = getimagesize(storage_path().'/app/'.$p->disk.'/'.$p->source);
            }

        }

        return $std;

    }



    public function createNewSiteArticle($data)
    {


        $this->create([
            'category_id'=>$data['art']['category_id'],
            'title'=>$data['art']['title'],
            'alias'=>str_slug($data['art']['title'],'-'),
            'intro_image'=>(!is_null($data['art']['intro_image']))?$data['art']['intro_image']['imagename']:'',
            'disk'=>(!is_null($data['art']['intro_image']))?$data['art']['intro_image']['disk']:null,
            'intro'=>$data['art']['introtext'],
            'content'=>$data['art']['fulltext'],
            'target_type'=>$data['type'],
            'published_at'=>Carbon::now()
        ]);

        return $data;

    }


    public function createNewExternalArticle($data)
    {

        $this->create([
            'category_id'=>$data['art']['category_id'],
            'title'=>$data['art']['title'],
            'alias'=>str_slug($data['art']['title'],'-'),
            'intro_image'=>(!is_null($data['art']['intro_image']))?$data['art']['intro_image']['imagename']:'',
            'disk'=>(!is_null($data['art']['intro_image']))?$data['art']['intro_image']['disk']:null,
            'intro'=>$data['art']['introtext'],
            'content'=>$data['art']['fulltext'],
            'external_url'=>$data['art']['external_url'],
            'target_type'=>$data['type'],
            'published_at'=>Carbon::now()
        ]);

        return $data;

    }


    public function updateSiteArticle($data)
    {

        $array = [
            'category_id'=>$data['art']['category_id'],
            'title'=>$data['art']['title'],
            'alias'=>str_slug($data['art']['title'],'-'),
            'intro_image'=>(!is_null($data['art']['intro_image']))?$data['art']['intro_image']['imagename']:'',
            'disk'=>(!is_null($data['art']['intro_image']))?$data['art']['intro_image']['disk']:null,
            'intro'=>$data['art']['introtext'],
            'content'=>$data['art']['fulltext'],
            'target_type'=>$data['type'],
            'published_at'=>Carbon::now()
        ];


        $this->update($array,$data['art']['id']);

        return $data;

    }


    public function updateExternalArticle($data)
    {

        $array = [
            'category_id'=>$data['art']['category_id'],
            'title'=>$data['art']['title'],
            'alias'=>str_slug($data['art']['title'],'-'),
            'intro_image'=>(!is_null($data['art']['intro_image']))?$data['art']['intro_image']['imagename']:'',
            'disk'=>(!is_null($data['art']['intro_image']))?$data['art']['intro_image']['disk']:null,
            'intro'=>$data['art']['introtext'],
            'content'=>$data['art']['fulltext'],
            'external_url'=>$data['art']['external_url'],
            'target_type'=>$data['type'],
            'published_at'=>Carbon::now()
        ];

        $this->update($array,$data['art']['id']);

        return $data;

    }


}
