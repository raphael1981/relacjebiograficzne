<?php

namespace App\Recordgables;

use App\Entities\Record;
use Illuminate\Support\Facades\DB;

class RecordgablesRepository{


    public static function getInstance(){
        return new self();
    }

    public function linkAllToAll($array){

        $raport = [];

        $farray = $array;

        foreach($array as $key=>$value){

            $r = Record::find($value);

            if(in_array($value,$array)){

                unset($farray[$key]);

                foreach($farray as $k=>$v){

                    if($this->checkIsLinkExist($value, $v)){
                        $r->recordsMorphedByMany()->save(Record::find($v));
                    }

                }

                $farray = $array;

            }


        }

        return $raport;

    }


    public function linkManyToOne($id, $array){

        $r = Record::find($id);

        foreach($array as $key=>$rid){

            if($this->checkIsLinkExist($id, $rid)){
                $r->recordsMorphedByMany()->save(Record::find($rid));
            }

        }

    }


    private function checkIsLinkExist($id, $lid){


        $boolean = true;

        foreach(Record::find($id)->recordsMorphedByMany()->get() as $key=>$record){


            if($record->id==$lid){
                $boolean = false;
            }

        }

        return $boolean;

    }



//    public function checkAndRemoveDouble(){
//
//        $array = [];
//
//        $results = DB::select("select
//                                `recordgables`.`recordgables_id`,
//                                `recordgables`.`record_id`,
//                                `recordgables`.`recordgables_type`
//                                from `recordgables`
//                                where `recordgables`.`recordgables_type`='App\\\Entities\\\Record'
//                                GROUP BY `recordgables_id`, `record_id`, `recordgables_type`"
//        );
//
//        foreach($results as $key=>$res){
//
//            array_push($array, $res->record_id);
//
//        }
//
//
//        dd($array);
//
//
//    }


}