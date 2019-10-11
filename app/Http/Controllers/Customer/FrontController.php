<?php

namespace App\Http\Controllers\Customer;

use App\Entities\Gallery;
use App\Entities\Thread;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Repositories;
use App\Helpers\CustomHelp;

class FrontController extends Controller
{

    private $article;
    private $hookcontent;


    public function __construct(Repositories\ArticleRepositoryEloquent $article, Repositories\HookContentRepositoryEloquent $hookcontent)
    {
        $this->article = $article;
        $this->hookcontent = $hookcontent;
    }

    public function indexAction(Request $request){


        $searchform = $searchform = view('front.scenes.home.content',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false
        ]);



        return view('front.scenes.masterhome', [

            'content'=>$searchform,
            'title'=>'Relacje biograficzne | Aktualności na stronie głównej',
            'controller'=>'front/home/home.controller.js',
            'breadcrumbs'=>null

        ]);

    }


    public function indexSearch(Request $request){


        $searchform = view('front.scenes.search.search',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false
        ]);

        return view('front.scenes.master', [

            'content'=>$searchform,
            'title'=>'Relacje biograficzne | Wyszukiwarka w transkrypcjach i w opisach zasobów ikonograficznych',
            'controller'=>'front/search/search.controller.js',
            'breadcrumbs'=>null

        ]);

    }


    public function indexArticle($category_slug, $article_slug, Request $request){

        $art = $this->article->parseSlugGetDataOnlyArticle($article_slug);

        $data = view('front.scenes.article.content',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false,
            'article'=>$art
        ]);

        $bc = [
            [
                'name'=>'Strona główna',
                'active'=>false,
                'url'=>'/'
            ],
            [
                'name'=>'Artykuł: "'.$art->title.'"',
                'active'=>true
            ]
        ];

        return view('front.scenes.master', [

            'content'=>$data,
            'title'=>'Relacje biograficzne | '.$art->title,
            'controller'=>'front/article/article.controller.js',
            'breadcrumbs'=>$bc

        ]);

    }



    public function indexInterviewees(){

        $bc = [
            [
                'name'=>'Strona główna',
                'active'=>false,
                'url'=>'/'
            ],
            [
                'name'=>'Świadkowie',
                'active'=>true
            ]
        ];

        $data = view('front.scenes.interviewees.content',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false
        ]);


        return view('front.scenes.master', [

            'content'=>$data,
            'title'=>'Relacje biograficzne | Indeks świadków ',
            'controller'=>'front/interviewees/interviewees.controller.js',
            'breadcrumbs'=>$bc

        ]);


    }



    public function indexGalleries(){

        $bc = [
            [
                'name'=>'Strona główna',
                'active'=>false,
                'url'=>'/'
            ],
            [
                'name'=>'Galerie',
                'active'=>true
            ]
        ];

        $data = view('front.scenes.galleries.content',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false
        ]);


        return view('front.scenes.master', [

            'content'=>$data,
            'title'=>'Relacje biograficzne | Galerie tematyczne',
            'controller'=>'front/galleries/galleries.controller.js',
            'breadcrumbs'=>$bc

        ]);

    }


    public function indexGallery($slug){

        $id = CustomHelp::parseSlugGetId($slug);

        $gallery = Gallery::find($id);

        $data = view('front.scenes.gallery.content',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false,
            'id'=>$id,
            'name'=>$gallery->name,
            'mode'=>$gallery->mode
        ]);

        $bc = [
            [
                'name'=>'Strona główna',
                'active'=>false,
                'url'=>'/'
            ],
            [
                'name'=>'Galeria',
                'active'=>false,
                'url'=>'/galerie'
            ],
            [
                'name'=>'Galeria o tematyce: "'.$gallery->name.'"',
                'active'=>true
            ]
        ];

        return view('front.scenes.mastergallery', [

            'content'=>$data,
            'title'=>'Relacje biograficzne | Galeria - '.$gallery->name,
            'controller'=>'front/gallery/gallery.controller.js',
            'breadcrumbs'=>$bc
        ]);

    }


    public function indexThreads(){

        $bc = [
            [
                'name'=>'Strona główna',
                'active'=>false,
                'url'=>'/'
            ],
            [
                'name'=>'Tematy',
                'active'=>true
            ]
        ];


        $data = view('front.scenes.threads.content',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false,

        ]);

        return view('front.scenes.master', [

            'content'=>$data,
            'title'=>'Relacje biograficzne | Nagrania pogrupowane tematycznie',
            'controller'=>'front/threads/threads.controller.js',
            'breadcrumbs'=>$bc
        ]);

    }

    public function indexThread($thred_slug){



        $id = CustomHelp::parseSlugGetId($thred_slug);

        $thread = Thread::find($id);

        $data = view('front.scenes.threads.single',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false,
            'tid'=>$id,
            'thread'=>$thread
        ]);

        $bc = [
            [
                'name'=>'Strona główna',
                'active'=>false,
                'url'=>'/'
            ],
            [
                'name'=>'Tematy',
                'active'=>false,
                'url'=>'/tematy'
            ],
            [
                'name'=>'Spis nagrań w tematyce: "'.$thread->name.'"',
                'active'=>true
            ]
        ];

        return view('front.scenes.master', [

            'content'=>$data,
            'title'=>'Relacje biograficzne | Nagrania o tematyce: '.$thread->name,
            'controller'=>'front/threads/thread.controller.js',
            'breadcrumbs'=>$bc
        ]);

    }


    public function indexProject(){

        $bc = [
            [
                'name'=>'Strona główna',
                'active'=>false,
                'url'=>'/'
            ],
            [
                'name'=>'O projekcie',
                'active'=>true
            ]
        ];

        $data = view('front.scenes.custom.aboutproject',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false,
            'hook'=>'about-project'
        ]);

        return view('front.scenes.masterproject', [

            'content'=>$data,
            'title'=>'Relacje biograficzne | O projekcie',
            'controller'=>'front/custom/about.project.controller.js',
            'breadcrumbs'=>$bc
        ]);

    }



    public function indexHookCms($slug){


        $data = view('front.scenes.cms.articlehook',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false,
            'slug'=>$slug,
            'htmldata'=>$this->hookcontent->renderHookContentDataHtml($slug)
        ]);


        return view('front.scenes.master', [

            'content'=>$data,
            'title'=>'Relacje biograficzne',
            'controller'=>'front/cms/articlehook.controller.js',
            'breadcrumbs'=>null

        ]);

    }


    public function indexImages(Request $request){


        $data = view('front.scenes.images.search',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false,
        ]);

        return view('front.scenes.masterimages', [

            'content'=>$data,
            'title'=>'Relacje biograficzne',
            'controller'=>'front/images/images.controller.js',
            'breadcrumbs'=>null
        ]);

    }




    public function indexAdvancedSearch(Request $request){

        $searchform = view('front.scenes.advancedsearch.search',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false
        ]);

        return view('front.scenes.masteradvanced', [

            'content'=>$searchform,
            'title'=>'Relacje biograficzne',
            'controller'=>'front/advancedsearch/advancedsearch.controller.js',
            'breadcrumbs'=>null

        ]);

    }



    public function indexSearchElasticsearch(Request $request){

        $bc = [
            [
                'name'=>'Strona główna',
                'active'=>false,
                'url'=>'/'
            ],
            [
                'name'=>'Wyszukaj',
                'active'=>true
            ]
        ];

//        echo $request->server('HTTP_REFERER');

        $searchform = view('front.scenes.elasticsearch.search.search',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false
        ]);


        return view('front.scenes.elasticsearch.master', [

            'content'=>$searchform,
            'title'=>'Relacje biograficzne | Wyszukiwarka w transkrypcjach i w opisach zasobów ikonograficznych',
            'controller'=>'front/elasticsearch/search.controller.js',
            'breadcrumbs'=>$bc

        ]);

    }



    public function indexImagesElasticsearch(Request $request){

        $bc = [
            [
                'name'=>'Strona główna',
                'active'=>false,
                'url'=>'/'
            ],
            [
                'name'=>'Galerie',
                'active'=>true
            ]
        ];

        $data = view('front.scenes.elasticsearch.images.content',[
            'auth'=>(Auth::guard('customer')->check() || Auth::guard()->check())?true:false
        ]);


        return view('front.scenes.elasticsearch.masterimages', [

            'content'=>$data,
            'title'=>'Relacje biograficzne',
            'controller'=>'front/elasticsearch/images.controller.js',
            'breadcrumbs'=>$bc

        ]);

    }



}
