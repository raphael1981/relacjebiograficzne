<?php

namespace App\Http\Controllers\Search;

use App\Helpers\SearchHelp;
use App\Repositories\CustomerRepositoryEloquent;
use App\Search\SearchRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class AjaxController extends Controller
{

    private $search;

    public function __construct(SearchRepository $search)
    {
        $this->search = $search;
    }

    public function getData(Request $request){


        $data = null;

        switch($request->get('tsearch')){

            case 'trans':


                if($request->get('searchmode')=='regex'){
                    $data = $this->search->findRecords($request->get('frase'));
                }

                if($request->get('searchmode')=='perfect'){
                    $data = $this->search->findRecordsPerfect($request->get('frase'));
                }


                break;

            case 'author':


                break;

        }

        return $data;

    }


    public function cacheData(Request $request){

        $data = null;

        switch($request->get('tsearch')){

            case 'trans':

                if($request->get('searchmode')=='regex') {
                    $data = $this->search->findRecordsCache($request->get('frase'));
                }

                if($request->get('searchmode')=='perfect'){
                    $data = $this->search->findRecordsPerfectCache($request->get('frase'));
                }

                break;

            case 'author':


                break;

        }

        return $data;

    }


    public function getNextCacheData(Request $request){


        switch($request->get('type')){

            case 'perfect':

                $frase = $request->get('frase');

                if(Cache::has('perfect:'.$frase)) {
                    $cache = Cache::get('perfect:'.$frase);
                }

                if(key_exists($request->get('next'), $cache)){
                    return $cache[$request->get('next')];
                }else{
                    return response(1,200);
                }

                break;

            case 'regex':

                $frase = SearchHelp::makeRegexSearch($request->get('frase'));

                if(Cache::has($frase)) {
                    $cache = Cache::get($frase);
                }

                if(key_exists($request->get('next'), $cache)){
                    return $cache[$request->get('next')];
                }else{
                    return response(1,200);
                }

                break;

        }




    }


}
