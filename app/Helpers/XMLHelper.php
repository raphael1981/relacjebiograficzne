<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class XMLHelper{


    private $tid;
    private $record_rpo;
    private $fragment_rpo;

    private $record = null;
    private $fragments = null;
    private $xml = null;
    private $xlength = 0;



    public function __construct($tid)
    {
        $this->tid = $tid;
        $this->record_rpo = App::make('App\Repositories\RecordRepositoryEloquent');
        $this->fragment_rpo = App::make('App\Repositories\FragmentRepositoryEloquent');

    }

    public static function xmlCreatorInstance($tid){
        return new self($tid);
    }


    /*
     * Prepare Transcription Record
     */

    public function prepareTranscription(){

        $this->record = $this->record_rpo->find($this->tid);

    }


    /*
     * Prepare Transcription Fragments
     */

    public function prepareTranscriptionFragments(){

        $this->fragments = $this->record->fragments()->get();

    }


    /*
     * Get Transcription
     */

    public function getTranscription(){

        return $this->record;

    }


    /*
     * Get Transcription Fragments
     */

    public function getTranscriptionFragments(){

        return $this->fragments;

    }


    /*
     * Get XML String
     */

    public function getXMLString(){

        return $this->xml;

    }


    /*
     * Get XML String Length
     */

    public function getXMLStringLength(){

        return $this->xlength;

    }


    /*
     * Create XML String
     */


    public function createXMLString(){

        if(!is_null($this->record) && !is_null($this->fragments)){


            $xml = '';
            $xml .= '<article>';

            foreach($this->fragments as $key=>$frg){

                $xml .= '<section>';

                $xml .= '<time>'.$frg->start.'</time>';

                $xml .= $frg->content;

                $xml .= '</section>';

            }

            $xml .= '</article>';

            $this->xml = '<?xml version="1.0" encoding="utf-8"?>';
            $this->xml .= $xml;
            $this->xlength = strlen($this->xml);



        }else{

            abort(403, 'Wykonaj wczesniej metody prepareTranscription i prepareTranscriptionFragments w klasie XMLHelper');

        }

        return $this->xml;

    }


    public static function readTranscriptionXML($xml)
    {

        $reader = new \XMLReader;
        $reader->open($xml);

        $array = [];

        $re = '/\<time\>([0-9]*)\<\/time\>/';

        $i = 0;

        $s = false;
        $t = false;

        while ($reader->read()) {


            if($s && $t){
                $s = false;
                $t = false;
            }

            if ($reader->nodeType == \XMLReader::ELEMENT) {

                if($reader->name == 'section'){

                    $string = $reader->readInnerXML();

                    $array[$i] = [
                        'content' => trim(preg_replace($re,'',$string)),
                    ];

                    $s=true;

                }

                if($reader->name == 'time'){

                    $array[$i]['time'] = $reader->readString();

                    $t=true;

                }


                if($s && $t){
                    $i++;
                }

            }
        }

        return $array;

    }

	public static function sec2Time($init){		
			$hours = sprintf("%02d", floor($init / 3600));
			$minutes = sprintf("%02d", floor(($init / 60) % 60));
		    $seconds = sprintf("%02d", $init % 60);
			return $hours.':'.$minutes.':'.$seconds;
	}

}