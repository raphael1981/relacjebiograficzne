<?php

namespace App\Http\Controllers\Super;

use App\Entities\DeleteBackup;
use App\Entities\Fragment;
use App\Entities\Interviewee;
use App\Entities\Place;
use App\Entities\Record;
use App\Entities\Redactor;
use App\Entities\Tag;
use App\Entities\Interval;
use App\Helpers\XMLHelper;
use App\Taggables\TaggablesRepository;
use App\Placegables\PlacegablesRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use Ixudra\Curl\Facades\Curl;


class RecordsController extends Controller
{

    private $record;
    private $fragment;
    private $interval;

    public function __construct(Repositories\RecordRepositoryEloquent $record, Repositories\FragmentRepositoryEloquent $fragment, Repositories\IntervalRepositoryEloquent $interval)
    {

        $this->record = $record;
        $this->fragment = $fragment;
        $this->interval = $interval;
        $this->middleware('super', ['exept' => []]);

    }


    public function indexAction()
    {

        $content = view('super.record.content');

        return view('super.masterrecord', [
            'content' => $content,
            'title' => 'AHM - Super Administrator - Transkrypcje',
            'controller' => 'admin/super/records.controller.js'
        ]);

    }


    public function getRecords(Request $request)
    {

        return $this->record->searchByCriteria($request->all());

    }


    public function getRecord($id)
    {


        $r = Record::find($id);

        $filename = $r->xmltrans;

        $xml = XMLHelper::readTranscriptionXML(config('services')['timesign_xmlpath'] .'/'. $filename);

        $r->xmltrans = new \stdClass();
        $r->xmltrans->xmlurl = url('xml/' . $filename);
        $r->xmltrans->xmldata = $xml;
        $r->xmltrans->filename = $filename;
//        $r->xmltrans->filepath = base_path('public/xml') . '/' . $filename;


        return $r;

    }


    public function getRecordInterviewees($id)
    {

        return Record::find($id)->interviewees()->get();

    }


    public function getRecordRedactors($id)
    {

        return Record::find($id)->redactors()->get();

    }


    public function updateData(Request $request)
    {

        if ($request->get('field') == 'status') {
            $this->record->update([$request->get('field') => $request->get('value'), 'published_at' => Carbon::now()], $request->get('id'));
        } else {
            $this->record->update([$request->get('field') => $request->get('value')], $request->get('id'));
        }

        if(!is_null($request->get('es'))){

            $response = Curl::to('http://localhost:9200/records/elements/'.$request->get('id').'/_update')
                ->withData( [
                    [
                        "script"=>[
                            "source"=>"ctx._source.record_status = ".$request->get('value')
                        ]
                    ]
                ])
                ->asJson()
                ->post();

            return json_encode($response);
        }

        return response(null, 200);

    }


    public function getFullRecordData($id)
    {

        $obj = json_decode($this->record->getFullRecordDataById($id));
        $obj->tagsbase = Tag::orderBy('name','desc')->get();
        $obj->intervalsbase = Interval::all();
        $obj->placebase = Place::all();

        return json_encode($obj);

    }


    public function getFullRecordDataIntervals($id)
    {

        $obj = json_decode($this->record->getFullRecordDataById($id));
        $obj->intervalsbase = Interval::all();

        return json_encode($obj);

    }


    public function updateRecordTags(Request $request)
    {


        $return_tag = false;
        $tags = [];

        foreach ($request->get('tags') as $key => $tag) {

            $tag_check = Tag::where('name', $tag['name']);

            if ($tag_check->count() > 0) {

                if ($tag_check->first()->id == $tag['id']) {
                    array_push($tags, Tag::find($tag['id']));
                }

            } else {
                $t = Tag::create(['name' => $tag['name']]);
                $return_tag = true;
                array_push($tags, Tag::find($t->id));
            }

        }

        $taggables = TaggablesRepository::getInstance();
        $taggables->clearTagsFromElementAndUpdate(Record::find($request->get('id')), $tags);

        if (!is_null($request->get('action')) && $request->get('action') == 'remove') {

            if (!isset($request->get('tag')['id'])) {
                $tag = Tag::where('name', $request->get('tag')['name'])->first();
            } else {
                $tag = Tag::find($request->get('tag')['id']);
            }

            $taggables->removeWhenTagHasNotLinkedElements($tag);

        }

        return ($return_tag) ? response($t) : response(null, '200');

    }


    public function updateFragmentTags(Request $request)
    {


        if($request->get('action')=='remove'){
            Fragment::find($request->get('id'))->tags()->detach($request->get('tag')['id']);
        }else {
            if (Fragment::find($request->get('id'))->tags()->where('id', $request->get('tag')['id'])->count() == 0) {
                Fragment::find($request->get('id'))->tags()->attach($request->get('tag')['id']);
            }
        }

        return $request->all();

//        $return_tag = false;
//        $tags = [];
//
//        foreach ($request->get('tags') as $key => $tag) {
//
//
//            $tag_check = Tag::where('name', $tag['name']);
//
//            if ($tag_check->count() > 0) {
//
//                $ctag = $tag_check->first();
//
//                if ($ctag->id == $tag['id']) {
//                    array_push($tags, Tag::find($tag['id']));
//                }
//
//            } else {
//                $t = Tag::create(['name' => $tag['name']]);
//                $return_tag = true;
//                array_push($tags, Tag::find($t->id));
//            }
//
//        }
//
//        $taggables = TaggablesRepository::getInstance();
//        $taggables->clearTagsFromElementAndUpdate(Fragment::find($request->get('id')), $tags);

//        if (!is_null($request->get('action')) && $request->get('action') == 'remove') {
//
//            if (!isset($request->get('tag')['id'])) {
//                $tag = Tag::where('name', $request->get('tag')['name'])->first();
//            } else {
//                $tag = Tag::find($request->get('tag')['id']);
//            }
//
//            $taggables->removeWhenTagHasNotLinkedElements($tag);
//
//        }


//        return ($return_tag) ? response($t) : response(null, '200');

    }


    public function updateFragmentPlaces(Request $request)
    {
        if($request->get('action')=='remove') {
            Fragment::find($request->get('id'))->places()->detach($request->get('tag')['id']);
        }else {

            if(Place::where('name',$request->get('tag')['name'])->count()==0){

                $t = Place::create([
                   'name'=> $request->get('tag')['name']
                ]);

                Fragment::find($request->get('id'))->places()->attach($t->id);


            }else{
                if (Fragment::find($request->get('id'))->places()->where('id', $request->get('tag')['id'])->count() == 0) {
                    Fragment::find($request->get('id'))->places()->attach($request->get('tag')['id']);
                }
            }



        }

        return $request->all();


//        $return_place = false;
//        $places = [];
//
//        foreach ($request->get('places') as $key => $place) {
//
//
//            $place_check = Place::where('name', $place['name']);
//
//            if ($place_check->count() > 0) {
//
//                $ctag = $place_check->first();
//
//                if ($ctag->id == $place['id']) {
//                    array_push($places, Place::find($place['id']));
//                }
//
//            } else {
//                $p = Place::create(['name' => $place['name']]);
//                $return_place = true;
//                array_push($places, Place::find($p->id));
//            }
//
//        }
//
//        $placegables = PlacegablesRepository::getInstance();
//        $placegables->clearPlacesFromElementAndUpdate(Fragment::find($request->get('id')), $places);

//        if (!is_null($request->get('action')) && $request->get('action') == 'remove') {
//
//            if (!isset($request->get('tag')['id'])) {
//                $tag = Tag::where('name', $request->get('tag')['name'])->first();
//            } else {
//                $tag = Tag::find($request->get('tag')['id']);
//            }
//
//            $taggables->removeWhenTagHasNotLinkedElements($tag);
//
//        }


        return ($return_place) ? response($p) : response(null, '200');

    }


    public function addNewRecord(Request $request)
    {

        $id = $this->record->createNewRecordGetId($request->all());

        if (!is_null($request->get('xmltrans'))) {
            $this->createFragmentsFromArray($id, $request->get('xmltrans')['xmldata']);
        }

        foreach ($request->get('interviewees') as $key => $value) {
            Record::find($id)->interviewees()->attach(Interviewee::find($value));
        }

        foreach ($request->get('redactors') as $key => $value) {
            Record::find($id)->redactors()->attach(Redactor::find($value));
        }

        return response('{"success":true}', 200, ['Content-type' => 'application/json']);

    }


    private function createFragmentsFromArray($id, $array)
    {

        foreach ($array as $key => $value) {
            Fragment::create([
                'record_id' => $id,
                'content' => $value['content'],
                'start' => $value['time'],
                'ord' => $key
            ]);
        }

    }


    public function updateEditRecord(Request $request)
    {

        switch ($request->get('uploadstatus')) {

            case 'original':

                $this->record->update([
                    'title' => $request->get('title'),
                    'alias' => str_slug($request->get('title'), '-'),
                    'signature' => $request->get('title'),
                    'source' => $request->get('source')['filename'],
                    'xmltrans' => $request->get('oldxmltrans')['filename'],
                    'description' => $request->get('description'),
                    'summary' => $request->get('summary'),
                    'duration' => $request->get('duration'),
                    'type' => $request->get('type')

                ], $request->get('id'));
                $r = Record::find($request->get('id'));
                $r->interviewees()->detach();
                $r->interviewees()->attach($request->get('interviewees'));
                $r->redactors()->detach();
                $r->redactors()->attach($request->get('redactors'));

                break;


            case 'new':

                $this->record->update([
                    'title' => $request->get('title'),
                    'alias' => str_slug($request->get('title'), '-'),
                    'signature' => $request->get('title'),
                    'source' => $request->get('source')['filename'],
                    'xmltrans' => $request->get('xmltrans')['filename'],
                    'description' => $request->get('description'),
                    'summary' => $request->get('summary'),
                    'duration' => $request->get('duration'),
                    'type' => $request->get('type')

                ], $request->get('id'));
                $this->record->updateSortString($request->get('id'));
                $r = Record::find($request->get('id'));
                $r->interviewees()->detach();
                $r->interviewees()->attach($request->get('interviewees'));
                $r->redactors()->detach();
                $r->redactors()->attach($request->get('redactors'));
                $this->fragment->clearFragmentsCreateNewByXML($request->all());


                break;

        }

        return response('{"success":true}', 200, ['Content-Type' => 'application/json']);

    }


    public function checkIsInterval(Request $request)
    {

        return $this->interval->checkIsInterval($request->all());

    }


    public function addInterval(Request $request)
    {

        $checkdata = \GuzzleHttp\json_decode($this->interval->checkIsInterval($request->all()));

        if (count($checkdata->intervals) == 0) {
            $intv = $this->interval->createIntervalFromForData($request->all());
            Interval::find($intv->id)->fragments()->save(Fragment::find($request->get('id')));
            return response('{"success":true,"interval":' . Interval::find($intv->id) . '}', 200, ['Content-Type' => 'application/json']);
        } else {
            $checkdata->success = false;
            return response(\GuzzleHttp\json_encode($checkdata), 200, ['Content-Type' => 'application/json']);
        }


    }


    public function linkFragmentByInterval(Request $request)
    {

        $signs_count = Interval::find($request->get('id'))->fragments()->where('id', $request->get('fid'))->count();

        if ($signs_count == 0) {
            Interval::find($request->get('id'))->fragments()->save(Fragment::find($request->get('fid')));
            return response('{"success":true,"interval":' . Interval::find($request->get('id')) . '}', 200, ['Content-Type' => 'application/json']);
        } else {
            return response('{"success":false}', 200, ['Content-Type' => 'application/json']);
        }


    }


    public function linkFragmentIntervalRemove(Request $request)
    {
        Interval::find($request->get('iid'))->fragments()->detach($request->get('fid'));
        return $request->all();
    }


    public function updateGetFragmentIntervals(Request $request)
    {
        return Fragment::find($request->get('fid'))->intervals()->get();
    }


    public function getRaportBeforeDelete(Request $request)
    {

        $std = new \stdClass();
        $std->relations = [];

        foreach ($request->get('relations') as $key => $rel) {
            array_push($std->relations, [
                'data' => Record::find($request->get('id'))->{$rel['method']}()->get(),
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
                Record::find($request->get('id'))->{$rel['method']}()->detach();
            }
        }

        Record::find($request->get('id'))->delete();

        return $request->all();

    }


    public function deleteFragment($rid){

        $f = Fragment::find($rid);

        $f->intervals()->detach();
        $f->tags()->detach();
        $f->places()->detach();
        $f->delete();

    }

    public function updateContentFragment(Request $request){

        $f = $this->fragment->update([
            'content'=>$request->get('content')
        ],$request->get('rid'));

        return $f->content;
    }


    public function updateTimeFragment(Request $request){

        $f = $this->fragment->update([
            'start'=>$request->get('time')
        ],$request->get('fid'));

    }

    private function createJsonToDeleteArchive($data)
    {

        $std = new \stdClass();
        $std->relations = [];

        foreach ($data['relations'] as $key => $rel) {

            array_push($std->relations, [
                'data' => Record::find($data['id'])->{$rel['method']}()->get(),
                'name' => $rel['method']
            ]);

        }

        $std->element = Record::find($data['id']);

        return \GuzzleHttp\json_encode($std);


    }

}
