<?php

namespace App\Http\Controllers\Super;

use App\Entities\Period;
use App\Entities\Record;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;

class PeriodsController extends Controller
{
    private $period;

    public function __construct(Repositories\PeriodRepositoryEloquent $period){

        $this->middleware('super', ['exept'=>[]]);
        $this->period = $period;

    }

    public function indexAction(){

        $content = view('super.period.content');

        return view('super.masterperiod', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Epoki',
            'controller'=>'admin/super/periods.controller.js'
        ]);

    }



    public function getPeriods(Request $request){

        return $this->period->searchByCriteria($request->all());

    }

    public function checkIsPeriodExist(Request $request){

        $data = $request->all();

        $period = Period::where(function($q) use ($data){
            $q->where('name', '=', $data['value']);
            $q->where('name', '!=', $data['basevalue']);
        });

        if($period->count()>0){
            return response('{"success":false}', 200, ['Content-Type'=>'application/json']);
        }else{
            return response('{"success":true}', 200, ['Content-Type'=>'application/json']);
        }


    }



    public function changePeriodName(Request $request){

        $this->period->update(['name'=>$request->get('value'), 'alias'=>str_slug($request->get('value'))], $request->get('id'));

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function getFullPeriodData($id){

        return $this->period->getFullPeriodData($id);

    }

    public function removeLinkedPeriod(Request $request){

        $this->period->find($request->get('pid'))->records()->detach($request->get('rid'));

        return $request->all();
    }


    public function updateLinkedRecordsArray(Request $request){

        $update_array = [];

        foreach($request->get('n_val') as $ko=>$nr){

                if(!in_array($nr,$request->get('o_val'))){
                    array_push($update_array,$nr);
                }

        }

        Period::find($request->get('pid'))->records()->attach($update_array);

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


}
