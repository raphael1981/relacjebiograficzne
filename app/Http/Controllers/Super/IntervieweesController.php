<?php

namespace App\Http\Controllers\Super;

use App\Entities\Interviewee;
use App\Entities\Record;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use App\Entities\DeleteBackup;

class IntervieweesController extends Controller
{

    private $interviewee;

    public function __construct(Repositories\IntervieweeRepositoryEloquent $interviewee){

        $this->middleware('super', ['exept'=>[]]);
        $this->interviewee = $interviewee;

    }

    public function indexAction(){

        $content = view('super.interviewee.content');

        return view('super.masterinterviewees', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Świadkowie',
            'controller'=>'admin/super/interviewees.controller.js'
        ]);

    }

    public function getInterviewees(Request $request){

        return $this->interviewee->searchByCriteria($request->all());

    }


    public function getIntervieweeData($id){

        $model = Interviewee::find($id);
        $model->records = $model->records()->get();
        $portrait = [
          'fname'=> $model->portrait
        ];
        $model->portrait = $portrait;

        return $model;

    }


    public function updateData(Request $request){


        $this->interviewee->update([$request->get('field')=>$request->get('value')], $request->get('id'));

        return response(null, 200);
    }


    public function updateFullData($id, Request $request){

        $this->interviewee->updateIntervieweeData($request->all());

        $this->interviewee->updateSortString($id);

        Interviewee::find($id)->records()->detach();

        foreach($request->get('records') as $k=>$v){

            if(gettype($v)=='integer'){
                Interviewee::find($id)->records()->attach(Record::find($v));
            }else{
                Interviewee::find($id)->records()->attach(Record::find($v['id']));
            }



        }

        return response('{"success":true}', 200, ['Content-type'=>'application/json']);

    }


    private function checkIsInTax($iid, $rid){

        $bool = true;

        foreach(Interviewee::find($iid)->records() as $key=>$val){
            if($val->id==$rid){
                $bool=false;
            }
        }

        return $bool;

    }


    public function createNewInterviewee(Request $request){

        $model = $this->interviewee->createIntervieweeGetId($request->all());

        foreach($request->get('records') as $k=>$v){
            Interviewee::find($model->id)->records()->attach(Record::find($v));
        }

        $this->interviewee->updateSortString($model->id);

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function getRaportBeforeDelete(Request $request){

        $std = new \stdClass();
        $std->relations = [];

        foreach($request->get('relations') as $key=>$rel){
            array_push($std->relations,[
                'data'=>Interviewee::find($request->get('id'))->{$rel['method']}()->get(),
                'name'=>$rel['name']
            ]);
        }

        return \GuzzleHttp\json_encode($std);
    }


    public function deleteInterviewee(Request $request){

        $data = $this->createJsonToDeleteArchive($request->all());

        $backup = new DeleteBackup();
        $backup->data = $data;
        $backup->model = $request->get('model');
        $backup->save();

        foreach($request->get('relations') as $key=>$rel){
            Interviewee::find($request->get('id'))->{$rel['method']}()->detach();
        }

        Interviewee::find($request->get('id'))->delete();

        return $request->all();

    }


    private function createJsonToDeleteArchive($data){

        $std = new \stdClass();
        $std->relations = [];

        foreach($data['relations'] as $key=>$rel){

            array_push($std->relations,[
                'data'=>Interviewee::find($data['id'])->{$rel['method']}()->get(),
                'name'=>$rel['method']
            ]);

        }

        $std->element = Interviewee::find($data['id']);

        return \GuzzleHttp\json_encode($std);

    }

}
