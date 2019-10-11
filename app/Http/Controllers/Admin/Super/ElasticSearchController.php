<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\MonitorIndx;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Ring\Client\MockHandler;
use Elasticsearch\Client;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Repositories;

class ElasticSearchController extends Controller
{

    private $elasticsearch;
    private $monitor;

    public function __construct(Client $elasticsearch, Repositories\MonitorIndxRepositoryEloquent $monitor)
     {
         $this->elasticsearch = $elasticsearch;
         $this->monitor = $monitor;
     }

    public function indexAction()
    {

        $privileges = Auth::user()->privileges;

        if(is_null($privileges)){
            return;
        }
        $privileges_data = \GuzzleHttp\json_decode($privileges);

        if(!$privileges_data->full_index_data){
            return;
        }


        $content = view('super.elastic.content');

        return view('super.masterelastic', [
            'content'=>$content,
            'title'=>'AHM - Super Administrator - Indeksowanie',
            'controller'=>'admin/super/elastic.controller.js'
        ]);


//        $output = shell_exec('php '.base_path().'/artisan');
//        echo "<pre>$output</pre>";
    }


    public function indexMakeRecords(Request $request){

        $monitor = MonitorIndx::create([
            'type'=>'records'
        ]);

        $output = shell_exec('php '.base_path().'/artisan els:panel:records '.$monitor->id." > /dev/null 2>/dev/null &");

        return response('{"create":true, "raport":'.$monitor->id.'}',200,['Content-type'=>'application/json']);
    }


    public function indexGetRecords(Request $request){
        return MonitorIndx::where('start_at', '!=', null)->where('type','records')->orderBy('start_at', 'desc')->first();

    }


    public function indexForceMakeRecords(Request $request){

        MonitorIndx::where('type','records')->update(['status' => 1,'end_at'=>Carbon::now()]);

        $monitor = MonitorIndx::create([
            'type'=>'records'
        ]);

        $output = shell_exec('php '.base_path().'/artisan els:panel:records '.$monitor->id." > /dev/null 2>/dev/null &");

        return response('{"create":true, "raport":'.$monitor->id.'}',200,['Content-type'=>'application/json']);
    }




    public function indexMakeImages(Request $request){

        $monitor = MonitorIndx::create([
            'type'=>'images'
        ]);

        $output = shell_exec('php '.base_path().'/artisan els:panel:images '.$monitor->id." > /dev/null 2>/dev/null &");

        return response('{"create":true, "raport":'.$monitor->id.'}',200,['Content-type'=>'application/json']);
    }

    public function indexGetImages(Request $request){
        return MonitorIndx::where('start_at', '!=', null)->where('type','images')->orderBy('start_at', 'desc')->first();
    }


    public function indexForceMakeImages(Request $request){

        MonitorIndx::where('type','images')->update(['status' => 1,'end_at'=>Carbon::now()]);

        $monitor = MonitorIndx::create([
            'type'=>'images'
        ]);

        $output = shell_exec('php '.base_path().'/artisan els:panel:images '.$monitor->id." > /dev/null 2>/dev/null &");

        return response('{"create":true, "raport":'.$monitor->id.'}',200,['Content-type'=>'application/json']);
    }




    public function indexMakeGallery(Request $request){

        $monitor = MonitorIndx::create([
            'type'=>'galleries'
        ]);

        $output = shell_exec('php '.base_path().'/artisan els:panel:galleries '.$monitor->id." > /dev/null 2>/dev/null &");

        return response('{"create":true, "raport":'.$monitor->id.'}',200,['Content-type'=>'application/json']);
    }

    public function indexGetGallery(Request $request){
        return MonitorIndx::where('start_at', '!=', null)->where('type','galleries')->orderBy('start_at', 'desc')->first();
    }


    public function indexForceMakeGallery(Request $request){

        MonitorIndx::where('type','galleries')->update(['status' => 1,'end_at'=>Carbon::now()]);

        $monitor = MonitorIndx::create([
            'type'=>'galleries'
        ]);

        $output = shell_exec('php '.base_path().'/artisan els:panel:galleries '.$monitor->id." > /dev/null 2>/dev/null &");

        return response('{"create":true, "raport":'.$monitor->id.'}',200,['Content-type'=>'application/json']);
    }




    public function getMonitorById($id){
        return MonitorIndx::find($id);
    }


}
