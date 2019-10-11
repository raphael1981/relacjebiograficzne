<?php

namespace App\Http\Controllers\Customer;

use App\Entities\Record;
use App\Entities\Thread;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\XMLHelper;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomHelp;

class TranscriptionController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer', ['except' => ['indexNoAuthTranscription']]);

    }

	
	
    public function indexTranscription(Request $request, $type, $slug, $time=null){

		$thread = null;
		$temp = explode('-',$slug);
		$tab = array_shift($temp);
		$data = Record::find($tab);
		$interviewees = Record::find($tab)->interviewees()->get();

		$bc_middle = null;

		$fragments = Record::find($tab)->fragments()->get();
		$times = [];

		foreach($fragments as $key => $val){
			array_push($times, XMLHelper::sec2Time($val->start));
		}


		if(!is_null($request->server('HTTP_REFERER'))) {
			if(strpos($request->server('HTTP_REFERER'), config('services')['domains']['customers']) !== false) {
				$bc_middle = CustomHelp::getBeforeRouteFromFullHttp($request->server('HTTP_REFERER'));
			}
		}


		if(strpos('tematy',$bc_middle)!==false){
			$path_arr = explode('/',$bc_middle);
			$el_path = explode('-',$path_arr[1]);
			$return_thread = $el_path[1];
			if(preg_match('/[0-9]+/',$el_path[0])){
				$thread = Thread::find($el_path[0]);

			}
		}


		if(is_null($bc_middle)) {

			$bc = [
					[
							'name' => 'Strona główna',
							'active' => false,
							'url' => '/'
					],
					[
							'name' => 'Nagranie: "' . $data->title . '"',
							'active' => true
					]
			];

		}elseif($bc_middle=='wyszukiwanie'){

			$bc = [
					[
							'name' => 'Strona główna',
							'active' => false,
							'url' => '/'
					],
					[
							'name' => 'Wyszukaj',
							'active' => false,
							'url' => '/'.$bc_middle
					],
					[
							'name' => 'Nagranie: "' . $data->title . '"',
							'active' => true
					]
			];

		}elseif($bc_middle=='swiadkowie') {

			$bc = [
					[
							'name' => 'Strona główna',
							'active' => false,
							'url' => '/'
					],
					[
							'name' => 'Świadkowie',
							'active' => false,
							'url' => '/' . $bc_middle
					],
					[
							'name' => 'Nagranie: "' . $data->title . '"',
							'active' => true
					]
			];

		}elseif(!is_null($thread)){

			$bc = [
					[
							'name' => 'Strona główna',
							'active' => false,
							'url' => '/'
					],
					[
							'name' => 'Tematy',
							'active' => false,
							'url' => '/tematy'
					],
					[
							'name' => $thread->name,
							'active' => false,
							'url' => '/'.$return_thread
					],
					[
							'name' => 'Nagranie: "' . $data->title . '"',
							'active' => true
					]
			];

		}else{

			$bc = [
					[
							'name' => 'Strona główna',
							'active' => false,
							'url' => '/'
					],
					[
							'name' => 'Nagranie: "' . $data->title . '"',
							'active' => true
					]
			];

		}


		$recordcart = view('front.scenes.recordcards.record',[
				'data' => $data,
				'fragments' => $fragments,
				'times' => $times,
				'time' => $time ? $time : 0,
				'phrase' => isset($request->all()['frase']) ? urldecode($request->all()['frase']) : null,
				'stype' => isset($request->all()['stype']) ? urldecode($request->all()['stype']) : null,
				'interviewees' => $interviewees
		]);

			return view('front.scenes.masterrecord', [
					'content' => $recordcart,
					'title'=>'Relacje biograficzne',
					'controller'=>'front/record/record.controller.js',
					'breadcrumbs'=>$bc
			]);


//			$uri_intent = $request->path();
//			$times = [];
//
//			$recordcart = view('front.scenes.recordcards.recordnoauth',[
//					'data' => $data,
//					'times' => $times,
//					'interviewees' => $interviewees,
//					'uri_intent' => $uri_intent,
//					'smallbiography'=>$interviewees[0]->biography
//			]);
//
//
//
//			return view('front.scenes.masterrecord', [
//					'content' => $recordcart,
//					'title'=>'AHM',
//					'controller'=>'front/record/recordnoauth.controller.js'
//			]);


    }


	public function indexNoAuthTranscription(Request $request, $type, $slug, $time=null){

		$temp = explode('-',$slug);
		$tab = array_shift($temp);
		$data = Record::find($tab);
		$interviewees = Record::find($tab)->interviewees()->get();
		$uri_intent = $this->parseUrlGetFullUri($request->fullUrl());
		$times = [];


		$recordcart = view('front.scenes.recordcards.recordnoauth',[
				'data' => $data,
				'times' => $times,
				'interviewees' => $interviewees,
				'uri_intent' => $uri_intent,
				'smallbiography'=>$interviewees[0]->biography,
		]);

		$bc = [
				[
						'name'=>'Strona główna',
						'active'=>false,
						'url'=>'/'
				],
				[
						'name'=>'Biogram '.$interviewees[0]->name.' '.$interviewees[0]->surname,
						'active'=>true
				]
		];

		return view('front.scenes.masterrecord', [
				'content' => $recordcart,
				'title'=>'AHM',
				'controller'=>'front/record/recordnoauth.controller.js',
				'breadcrumbs'=>$bc
		]);

	}

		
	



	private function parseUrlGetFullUri($url){

		$array = explode('/', $url);

		$uri = '';

		if(isset($array[4])){

			if(!isset($array[5])) {
				$uri .= $array[4];
			}else{
				$uri .= $array[4].'/';
			}

		}

		if(isset($array[5])){

			if(!isset($array[6])) {
				$uri .= $array[5];
			}else{
				$uri .= $array[5].'/';
			}

		}

		if(isset($array[6])){
			$uri .= $array[6];
		}


		return $uri;

	}

}
