<?php

namespace App\Http\Controllers\Super;

use App\Entities\DeleteBackup;
use App\Entities\Place;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;

class PlacesController extends Controller
{
    private $place;

    public function __construct(Repositories\PlaceRepositoryEloquent $place){

        $this->middleware('super', ['exept'=>[]]);
        $this->place = $place;

    }

    public function indexAction(){

        $content = view('super.place.content');

        return view('super.masterplace', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Epoki',
            'controller'=>'admin/super/places.controller.js'
        ]);

    }


    public function getPlaces(Request $request){

        return $this->place->searchByCriteria($request->all());

    }



    public function checkIsPlaceExist(Request $request){

        $data = $request->all();

        $place = Place::where(function($q) use ($data){
            $q->where('name', '=', $data['value']);
            $q->where('name', '!=', $data['basevalue']);
        });

        if($place->count()>0){
            return response('{"success":false}', 200, ['Content-Type'=>'application/json']);
        }else{
            return response('{"success":true}', 200, ['Content-Type'=>'application/json']);
        }


    }



    public function changePlaceName(Request $request){

        $this->place->update(['name'=>$request->get('value'), 'alias'=>str_slug($request->get('value'))], $request->get('id'));

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function createNewPlace(Request $request){


        $place = $this->place->create([
            'name'=>$request->get('name'),
            'lat'=>$request->get('lat'),
            'lng'=>$request->get('lng')
        ]);


        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }



    public function getRaportBeforeDelete(Request $request){

        $std = new \stdClass();
        $std->relations = [];

        foreach($request->get('relations') as $key=>$rel){


            if(isset($rel['extra'])){

                $elms = Place::find($request->get('id'))->{$rel['method']}()->get();

                foreach($elms as $k=>$el){
                    $elms[$k]->{$rel['extra']['key']} = $el->{$rel['extra']['method']}()->first()->{$rel['extra']['field']};
                }

                array_push($std->relations,[
                    'data'=>$elms,
                    'name'=>$rel['name']
                ]);

            }else{
                array_push($std->relations,[
                    'data'=>Place::find($request->get('id'))->{$rel['method']}()->get(),
                    'name'=>$rel['name']
                ]);
            }
        }

        return \GuzzleHttp\json_encode($std);

    }

    public function deletePlace(Request $request){

        $data = $this->createJsonToDeleteArchive($request->all());

        $backup = new DeleteBackup();
        $backup->data = $data;
        $backup->model = $request->get('model');
        $backup->save();

        foreach($request->get('relations') as $key=>$rel){
            Place::find($request->get('id'))->{$rel['method']}()->detach();
        }

        Place::find($request->get('id'))->delete();

        return $request->all();

    }


    private function createJsonToDeleteArchive($data){

        $std = new \stdClass();
        $std->relations = [];

        foreach($data['relations'] as $key=>$rel){

            array_push($std->relations,[
                'data'=>Place::find($data['id'])->{$rel['method']}()->get(),
                'name'=>$rel['method']
            ]);

        }

        $std->element = Place::find($data['id']);

        return \GuzzleHttp\json_encode($std);

    }


}
