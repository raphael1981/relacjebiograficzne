<?php

namespace App\Http\Controllers\Super;

use App\Entities\DeleteBackup;
use App\Entities\Record;
use App\Entities\Redactor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use App\Redactorgables\RedactorgablesRepository;

class RedactorsController extends Controller
{

    private $redactor;

    public function __construct(Repositories\RedactorRepositoryEloquent $redactor){

        $this->middleware('super', ['exept'=>[]]);
        $this->redactor = $redactor;


    }

    public function indexAction(){

        $content = view('super.redactor.content');

        return view('super.masterredactors', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Redaktorzy',
            'controller'=>'admin/super/redactors.controller.js'
        ]);

    }


    public function getRedactors(Request $request){

        return $this->redactor->searchByCriteria($request->all());

    }


    public function updateData(Request $request){

        $this->redactor->update([$request->get('field')=>$request->get('value')], $request->get('id'));

        return response(null, 200);

    }


    public function createNewRedactor(Request $request){

        $redactorgables = RedactorgablesRepository::getInstance();

        $red = $this->redactor->create([
            'name'=>$request->get('name'),
            'surname'=>$request->get('surname'),
            'email'=>$request->get('email'),
            'profession'=>$request->get('profession')['id']
        ]);


        foreach($request->get('records') as $k=>$r){

            $redactorgables->addRedactorToModelElement(Record::find($r), Redactor::find($red->id));

        }

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function updateAllDataRedactor(Request $request){

        $redactorgables = RedactorgablesRepository::getInstance();

        $red = $this->redactor->update([
            'name'=>$request->get('name'),
            'surname'=>$request->get('surname'),
            'email'=>$request->get('email'),
            'profession'=>$request->get('profession')['id']
        ],$request->get('id'));

        foreach($red->records()->get() as $k=>$r) {

            $redactorgables->removeRedactorFromModelElement(Record::find($r->id), Redactor::find($red->id));

        }

        foreach($request->get('records') as $k=>$r){

            $redactorgables->addRedactorToModelElement(Record::find($r), Redactor::find($red->id));

        }

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function getRedactor($id){

        $model = $this->redactor->find($id);
        $records = $model->records()->get();

        $recs = [];
        foreach($records as $rec) {
            $recs[] = $rec->id;
        }

        $model->records = $recs;

        return $model;

    }


    public function getRaportBeforeDelete(Request $request){

        $std = new \stdClass();
        $std->relations = [];

        foreach($request->get('relations') as $key=>$rel){
            array_push($std->relations,[
                'data'=>Redactor::find($request->get('id'))->{$rel['method']}()->get(),
                'name'=>$rel['name']
            ]);
        }

        return \GuzzleHttp\json_encode($std);
    }

    public function deleteRedactor(Request $request){

        $data = $this->createJsonToDeleteArchive($request->all());

        $backup = new DeleteBackup();
        $backup->data = $data;
        $backup->model = $request->get('model');
        $backup->save();

        foreach($request->get('relations') as $key=>$rel){
            Redactor::find($request->get('id'))->{$rel['method']}()->detach();
        }

        Redactor::find($request->get('id'))->delete();

        return $request->all();

    }

    private function createJsonToDeleteArchive($data){

        $std = new \stdClass();
        $std->relations = [];

        foreach($data['relations'] as $key=>$rel){

            array_push($std->relations,[
                'data'=>Redactor::find($data['id'])->{$rel['method']}()->get(),
                'name'=>$rel['method']
            ]);

        }

        $std->element = Redactor::find($data['id']);

        return \GuzzleHttp\json_encode($std);

    }


}
