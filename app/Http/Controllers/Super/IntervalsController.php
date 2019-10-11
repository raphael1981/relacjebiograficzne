<?php

namespace App\Http\Controllers\Super;

use App\Entities\DeleteBackup;
use App\Entities\Interval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;

class IntervalsController extends Controller
{

    private $interval;

    public function __construct(Repositories\IntervalRepositoryEloquent $interval){

        $this->middleware('super', ['exept'=>[]]);
        $this->interval = $interval;

    }


    public function indexAction(){

        $content = view('super.interval.content');

        return view('super.masterinterval', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Interwały czasowe',
            'controller'=>'admin/super/intervals.controller.js'
        ]);

    }

    public function getIntervals(Request $request){

        return $this->interval->searchByCriteria($request->all());

    }


    public function getRaportBeforeDelete(Request $request){

        $std = new \stdClass();
        $std->relations = [];

        foreach($request->get('relations') as $key=>$rel){


            if(isset($rel['extra'])){

                $elms = Interval::find($request->get('id'))->{$rel['method']}()->get();

                foreach($elms as $k=>$el){
                    $elms[$k]->{$rel['extra']['key']} = $el->{$rel['extra']['method']}()->first()->{$rel['extra']['field']};
                }

                array_push($std->relations,[
                    'data'=>$elms,
                    'name'=>$rel['name']
                ]);

            }else{
                array_push($std->relations,[
                    'data'=>Interval::find($request->get('id'))->{$rel['method']}()->get(),
                    'name'=>$rel['name']
                ]);
            }
        }

        return \GuzzleHttp\json_encode($std);
    }

    public function deleteInterval(Request $request){

        $data = $this->createJsonToDeleteArchive($request->all());

        $backup = new DeleteBackup();
        $backup->data = $data;
        $backup->model = $request->get('model');
        $backup->save();

        foreach($request->get('relations') as $key=>$rel){
            Interval::find($request->get('id'))->{$rel['method']}()->detach();
        }

        Interval::find($request->get('id'))->delete();

        return $request->all();

    }


    public function checkIsIntervalExist(Request $request){
        return $this->interval->checkIsInterval($request->all());
    }

    public function addInterval(Request $request){

        $intv = $this->interval->createIntervalFromForData($request->all());
        return response('{"success":true,"interval":' . Interval::find($intv->id) . '}', 200, ['Content-Type' => 'application/json']);



    }


    private function createJsonToDeleteArchive($data){

        $std = new \stdClass();
        $std->relations = [];

        foreach($data['relations'] as $key=>$rel){

            array_push($std->relations,[
                'data'=>Interval::find($data['id'])->{$rel['method']}()->get(),
                'name'=>$rel['method']
            ]);

        }

        $std->element = Interval::find($data['id']);

        return \GuzzleHttp\json_encode($std);

    }



}
