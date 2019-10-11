<?php

namespace App\Placegables;

use Illuminate\Support\Facades\DB;

class PlacegablesRepository{


    public static function getInstance(){
        return new self();
    }

    public function addPlaceToModelElement($object, $place){

        if($object->places()->where('place_id',$place->id)->count()==0) {
            $object->places()->save($place);
        }

    }


    public function addPlacesToModelElement($object, $places){

        foreach($places as $key=>$place){

            if($object->places()->where('place_id',$place->id)->count()==0) {
                $object->places()->save($place);
            }

        }

    }


    public function addPlaceModelElements($objects, $place){

        foreach($objects as $key=>$object){

            if($object->places()->where('place_id',$place->id)->count()==0) {
                $object->places()->save($place);
            }

        }

    }


    public function addPlacesToModelElements($objects, $places){

        foreach($objects as $key=>$object){

            foreach($places as $place) {

                if($object->places()->where('place_id',$place->id)->count()==0) {
                    $object->places()->save($place);
                }

            }

        }

    }


    public function removePlaceFromModelElement($object, $place){

        if($object->places()->where('place_id',$place->id)->count()!=0) {
            $object->places()->detach($place);
        }

    }


    public function removePlacesFromModelElement($object, $places){

        foreach($places as $key=>$place){

            if($object->places()->where('place_id',$place->id)->count()!=0) {
                $object->places()->detach($place);
            }

        }

    }


    public function removePlaceFromModelElements($objects, $places){

        foreach($objects as $key=>$object){

            foreach($places as $place) {

                if($object->places()->where('place_id',$place->id)->count()!=0) {
                    $object->places()->detach($place);
                }

            }

        }

    }


    public function removePlacesFromElements($objects, $places){

        foreach($objects as $key=>$object){

            foreach($places as $place) {

                if($object->places()->where('place_id',$place->id)->count()!=0) {
                    $object->places()->detach($place);
                }

            }

        }

    }


    public function clearPlacesFromElementAndUpdate($object, $places){

        $object->places()->detach();

        foreach($places as $key=>$place){

            if($object->places()->where('place_id',$place->id)->count()==0) {
                $object->places()->attach($place);
            }

        }

    }


    public function removeWhenPlaceHasNotLinkedElements($place){


        if($relations = DB::table('placegables')->where('place_id', $place->id)->count()==0){
            $place->delete();
        }

    }


}