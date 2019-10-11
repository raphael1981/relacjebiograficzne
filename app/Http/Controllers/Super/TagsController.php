<?php

namespace App\Http\Controllers\Super;

use App\Entities\DeleteBackup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use App\Entities\Tag;

class TagsController extends Controller
{
    private $tag;

    public function __construct(Repositories\TagRepositoryEloquent $tag){

        $this->middleware('super', ['exept'=>[]]);
        $this->tag = $tag;

    }

    public function indexAction(){

        $content = view('super.tag.content');

        return view('super.mastertag', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Słowa kluczowe',
            'controller'=>'admin/super/tags.controller.js'
        ]);

    }



    public function getTags(Request $request){

        return $this->tag->searchByCriteria($request->all());

    }

    public function checkIsTagExist(Request $request){

        $data = $request->all();

        $period = Tag::where(function($q) use ($data){
            $q->where('name', '=', $data['value']);
            $q->where('name', '!=', $data['basevalue']);
        });

        if($period->count()>0){
            return response('{"success":false}', 200, ['Content-Type'=>'application/json']);
        }else{
            return response('{"success":true}', 200, ['Content-Type'=>'application/json']);
        }


    }



    public function changeTagName(Request $request){

        $this->tag->update(['name'=>$request->get('value'), 'alias'=>str_slug($request->get('value'))], $request->get('id'));

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function getFullTagData($id){



    }



    public function addNewTag(Request $request){

        $count_name_tags = Tag::where('name',$request->get('name'))->count();

        if($count_name_tags==0) {

            $this->tag->create([
                'name' => $request->get('name'),
                'alias' => str_slug($request->get('name'), '-')
            ]);

            return response('{"success":true}', 200, ['Content-Type' => 'application/json']);

        }

        return response('{"success":false}', 200, ['Content-Type' => 'application/json']);

    }


    public function getRaportBeforeDelete(Request $request){

        $std = new \stdClass();
        $std->relations = [];

        foreach($request->get('relations') as $key=>$rel){
            if(isset($rel['extra'])){

                $elms = Tag::find($request->get('id'))->{$rel['method']}()->get();

                foreach($elms as $k=>$el){
                    $elms[$k]->{$rel['extra']['key']} = $el->{$rel['extra']['method']}()->first()->{$rel['extra']['field']};
                }

                array_push($std->relations,[
                    'data'=>$elms,
                    'name'=>$rel['name']
                ]);

            }else{
                array_push($std->relations,[
                    'data'=>Tag::find($request->get('id'))->{$rel['method']}()->get(),
                    'name'=>$rel['name']
                ]);
            }
        }

        return \GuzzleHttp\json_encode($std);

    }

    public function deleteTag(Request $request){

        $data = $this->createJsonToDeleteArchive($request->all());

        $backup = new DeleteBackup();
        $backup->data = $data;
        $backup->model = $request->get('model');
        $backup->save();

        foreach($request->get('relations') as $key=>$rel){
            Tag::find($request->get('id'))->{$rel['method']}()->detach();
        }

        Tag::find($request->get('id'))->delete();

        return $request->all();

    }


    private function createJsonToDeleteArchive($data){

        $std = new \stdClass();
        $std->relations = [];

        foreach($data['relations'] as $key=>$rel){

            array_push($std->relations,[
                'data'=>Tag::find($data['id'])->{$rel['method']}()->get(),
                'name'=>$rel['method']
            ]);

        }

        $std->element = Tag::find($data['id']);

        return \GuzzleHttp\json_encode($std);

    }
}
