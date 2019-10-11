<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FrontController extends Controller
{

    public function __construct(){

        $this->middleware('super', ['exept'=>[]]);

    }

    public function indexAction(){

        $content = view('super.home');

        return view('super.master', [
                    'content'=>$content,
                    'title'=>'AHM - Super Administrator - Współtwórcy',
                    'controller'=>'admin/super/front.controller.js'
        ]);


    }
	
	
		public function goToTranscription(Request $request){
		
		$data = array("code" => 'Ala ma kota');
		//$data = array("code" => $request->header('X-CSRF-TOKEN'));
		$data_string = json_encode($data);
		$url = 'http://czasooznaczacz.dsh.waw.pl';
		$ch = curl_init($url.'/auth');
        curl_setopt($ch, CURLOPT_POST, true);		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url.'/auth');
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
												'Content-Type: application/json',
											   'Content-Length: ' . strlen($data_string),
											   )
											  											   
        );                                                                                                                   
	
      $content = curl_exec($ch);
      $redirectURL = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);	        
	  curl_close($ch);
	  $rsp = json_decode($content);
	  dd($rsp);
	  //if($rsp->code == 'Ala ma kota'){
	  //  header("Location: ". $url);
	  //}
	}
	
	



}
