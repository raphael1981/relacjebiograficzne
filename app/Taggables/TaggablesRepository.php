<?php

namespace App\Taggables;

use Illuminate\Support\Facades\DB;

class TaggablesRepository{


    public static function getInstance(){
        return new self();
    }

    public function addTagToModelElement($object, $tag){

        if($object->tags()->where('tag_id',$tag->id)->count()==0) {
            $object->tags()->save($tag);
        }

    }


    public function addTagsToModelElement($object, $tags){

        foreach($tags as $key=>$tag){

            if($object->tags()->where('tag_id',$tag->id)->count()==0) {
                $object->tags()->save($tag);
            }

        }

    }


    public function addTagModelElements($objects, $tag){

        foreach($objects as $key=>$object){

            if($object->tags()->where('tag_id',$tag->id)->count()==0) {
                $object->tags()->save($tag);
            }

        }

    }


    public function addTagsToModelElements($objects, $tags){

        foreach($objects as $key=>$object){

            foreach($tags as $tag) {

                if($object->tags()->where('tag_id',$tag->id)->count()==0) {
                    $object->tags()->save($tag);
                }

            }

        }

    }


    public function removeTagFromModelElement($object, $tag){

        if($object->tags()->where('tag_id',$tag->id)->count()!=0) {
            $object->tags()->detach($tag);
        }

    }


    public function removeTagsFromModelElement($object, $tags){

        foreach($tags as $key=>$tag){

            if($object->tags()->where('tag_id',$tag->id)->count()!=0) {
                $object->tags()->detach($tag);
            }

        }

    }


    public function removeTagFromModelElements($objects, $tags){

        foreach($objects as $key=>$object){

            foreach($tags as $tag) {

                if($object->tags()->where('tag_id',$tag->id)->count()!=0) {
                    $object->tags()->detach($tag);
                }

            }

        }

    }


    public function removeTagsFromElements($objects, $tags){

        foreach($objects as $key=>$object){

            foreach($tags as $tag) {

                if($object->tags()->where('tag_id',$tag->id)->count()!=0) {
                    $object->tags()->detach($tag);
                }

            }

        }

    }


    public function clearTagsFromElementAndUpdate($object, $tags){

        $object->tags()->detach();

        foreach($tags as $key=>$tag){

            if($object->tags()->where('tag_id',$tag->id)->count()==0) {
                $object->tags()->attach($tag);
            }

        }

    }


    public function removeWhenTagHasNotLinkedElements($tag){


        if($relations = DB::table('taggables')->where('tag_id', $tag->id)->count()==0){
            $tag->delete();
        }

    }


}