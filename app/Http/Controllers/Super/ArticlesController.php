<?php

namespace App\Http\Controllers\Super;

use App\Entities\Article;
use App\Entities\DeleteBackup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;

class ArticlesController extends Controller
{
    private $article;

    public function __construct(Repositories\ArticleRepositoryEloquent $article){

        $this->middleware('super', ['exept'=>[]]);
        $this->article = $article;

    }

    public function indexAction(){

        $content = view('super.article.content');

        return view('super.masterarticles', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Galerie',
            'controller'=>'admin/super/articles.controller.js'
        ]);

    }

    public function getArticles(Request $request){

        return $this->article->searchByCriteria($request->all());

    }


    public function getArticle($id){

        return $this->article->find($id);

    }


    public function updateData(Request $request){


        $this->article->update([$request->get('field')=>$request->get('value')], $request->get('id'));

        return response(null, 200);

    }


    public function updateAritcleFullData(Request $request){


        switch($request->get('type')){

            case 'site':

                $this->article->updateSiteArticle($request->all());

                break;

            case 'external':

                $this->article->updateExternalArticle($request->all());

                break;

        }


        return $request->all();

    }


    public function createNewAritcle(Request $request){

        switch($request->get('type')){

            case 'site':

                $this->article->createNewSiteArticle($request->all());

                break;

            case 'external':

                $this->article->createNewExternalArticle($request->all());

                break;

        }


        return $request->all();

    }


    public function updatePublishDate($id, Request $request){

        $this->article->update(['published_at'=>gmdate('Y-m-d H:i:s', strtotime($request->get('date')))], $id);

        return $this->article->find($id)->published_at;

    }


    public function markMainArticle(Request $request){


        if($request->get('inverse')) {
            Article::where('main', 1)->update(['main' => 0]);
        }

        $this->article->update(['main'=>$request->get('value')], $request->get('id'));

        return response(null, 200);

    }


    public function getRaportBeforeDelete(Request $request)
    {

        $std = new \stdClass();
        $std->relations = [];

        foreach ($request->get('relations') as $key => $rel) {
            array_push($std->relations, [
                'data' => Article::find($request->get('id'))->{$rel['method']}()->get(),
                'name' => $rel['name']
            ]);
        }

        return \GuzzleHttp\json_encode($std);
    }

    public function deleteRecord(Request $request)
    {

        $data = $this->createJsonToDeleteArchive($request->all());

        $backup = new DeleteBackup();
        $backup->data = $data;
        $backup->model = $request->get('model');
        $backup->save();

        foreach ($request->get('relations') as $key => $rel) {
            if($rel['type']=='oneToMany'){

            }else {
                Article::find($request->get('id'))->{$rel['method']}()->detach();
            }
        }

        Article::find($request->get('id'))->delete();

        return $request->all();

    }

    private function createJsonToDeleteArchive($data)
    {

        $std = new \stdClass();
        $std->relations = [];

        foreach ($data['relations'] as $key => $rel) {

            array_push($std->relations, [
                'data' => Article::find($data['id'])->{$rel['method']}()->get(),
                'name' => $rel['method']
            ]);

        }

        $std->element = Article::find($data['id']);

        return \GuzzleHttp\json_encode($std);


    }


}
