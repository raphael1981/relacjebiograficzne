<?php

namespace App\Redactorgables;

use Illuminate\Support\Facades\DB;

class RedactorgablesRepository{


    public static function getInstance(){
        return new self();
    }

    public function addRedactorToModelElement($object, $redactor){

        if($object->redactors()->where('redactor_id',$redactor->id)->count()==0) {
            $object->redactors()->save($redactor);
        }

    }


    public function addRedactorsToModelElement($object, $redactors){

        foreach($redactors as $key=>$redactor){

            if($object->redactors()->where('redactor_id',$redactor->id)->count()==0) {
                $object->redactors()->save($redactor);
            }

        }

    }


    public function addRedactorModelElements($objects, $redactor){

        foreach($objects as $key=>$object){

            if($object->redactors()->where('redactor_id',$redactor->id)->count()==0) {
                $object->redactors()->save($redactor);
            }

        }

    }


    public function addRedactorsToModelElements($objects, $redactors){

        foreach($objects as $key=>$object){

            foreach($redactors as $redactor) {

                if($object->redactors()->where('redactor_id',$redactor->id)->count()==0) {
                    $object->redactors()->save($redactor);
                }

            }

        }

    }


    public function removeRedactorFromModelElement($object, $redactor){

        if($object->redactors()->where('redactor_id',$redactor->id)->count()!=0) {
            $object->redactors()->detach($redactor);
        }

    }


    public function removeRedactorsFromModelElement($object, $redactors){

        foreach($redactors as $key=>$redactor){

            if($object->redactors()->where('redactor_id',$redactor->id)->count()!=0) {
                $object->redactors()->detach($redactor);
            }

        }

    }


    public function removeRedactorFromModelElements($objects, $redactors){

        foreach($objects as $key=>$object){

            foreach($redactors as $redactor) {

                if($object->redactors()->where('redactor_id',$redactor->id)->count()!=0) {
                    $object->redactors()->detach($redactor);
                }

            }

        }

    }


    public function removeRedactorsFromElements($objects, $redactors){

        foreach($objects as $key=>$object){

            foreach($redactors as $redactor) {

                if($object->redactors()->where('redactor_id',$redactor->id)->count()!=0) {
                    $object->redactors()->detach($redactor);
                }

            }

        }

    }


    public function clearRedactorsFromElementAndUpdate($object, $redactors){

        $object->redactors()->detach();

        foreach($redactors as $key=>$redactor){

            if($object->redactors()->where('redactor_id',$redactor->id)->count()==0) {
                $object->redactors()->attach($redactor);
            }

        }

    }


    public function removeWhenRedactorHasNotLinkedElements($redactor){


        if($relations = DB::table('redactorgables')->where('redactor_id', $redactor->id)->count()==0){
            $redactor->delete();
        }

    }


}