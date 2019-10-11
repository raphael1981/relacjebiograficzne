<?php

namespace App\Http\Controllers\Super;

use App\Entities\Record;
use App\Entities\Thread;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use App\Entities\DeleteBackup;

class ThreadsController extends Controller
{
    private $thread;

    public function __construct(Repositories\ThreadRepositoryEloquent $thread){

        $this->middleware('super', ['exept'=>[]]);
        $this->thread = $thread;

    }

    public function indexAction(){

        $content = view('super.thread.content');

        return view('super.masterthread', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Tematy',
            'controller'=>'admin/super/threads.controller.js'
        ]);

    }



    public function getThreads(Request $request){

        return $this->thread->searchByCriteria($request->all());

    }

    public function checkIsThreadExist(Request $request){

        $data = $request->all();

        $period = Thread::where(function($q) use ($data){
            $q->where('name', '=', $data['value']);
            $q->where('name', '!=', $data['basevalue']);
        });

        if($period->count()>0){
            return response('{"success":false}', 200, ['Content-Type'=>'application/json']);
        }else{
            return response('{"success":true}', 200, ['Content-Type'=>'application/json']);
        }


    }



    public function changeThreadName(Request $request){

        $this->thread->update(['name'=>$request->get('value'), 'alias'=>str_slug($request->get('value'))], $request->get('id'));

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function getFullThreadData($id){

        return $this->thread->getFullThreadData($id);

    }

    public function removeLinkedThread(Request $request){

        $this->thread->find($request->get('tid'))->records()->detach($request->get('rid'));

        return $request->all();
    }



    public function updateLinkedRecordsArray(Request $request){

        $update_array = [];

        foreach($request->get('n_val') as $ko=>$nr){

            if(!in_array($nr,$request->get('o_val'))){
                array_push($update_array,$nr);
            }

        }

        Thread::find($request->get('tid'))->records()->attach($update_array);

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function addNewThread(Request $request){

        $this->thread->create([
            'name'=>$request->get('name'),
            'alias'=>str_slug($request->get('name'),'-')
        ]);

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function getRaportBeforeDelete(Request $request){

        $std = new \stdClass();
        $std->relations = [];

        foreach($request->get('relations') as $key=>$rel){
            array_push($std->relations,[
                'data'=>Thread::find($request->get('id'))->{$rel['method']}()->get(),
                'name'=>$rel['name']
            ]);
        }

        return \GuzzleHttp\json_encode($std);

    }

    public function deleteThread(Request $request){

        $data = $this->createJsonToDeleteArchive($request->all());

        $backup = new DeleteBackup();
        $backup->data = $data;
        $backup->model = $request->get('model');
        $backup->save();

        foreach($request->get('relations') as $key=>$rel){
            Thread::find($request->get('id'))->{$rel['method']}()->detach();
        }

        Thread::find($request->get('id'))->delete();

        return $request->all();

    }


    private function createJsonToDeleteArchive($data){

        $std = new \stdClass();
        $std->relations = [];

        foreach($data['relations'] as $key=>$rel){

            array_push($std->relations,[
                'data'=>Thread::find($data['id'])->{$rel['method']}()->get(),
                'name'=>$rel['method']
            ]);

        }

        $std->element = Thread::find($data['id']);

        return \GuzzleHttp\json_encode($std);

    }

}
