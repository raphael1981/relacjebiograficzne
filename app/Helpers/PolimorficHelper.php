<?php

namespace App\Helpers;


class PolimorficHelper{


    public static function removePolimorficRelations($object, array $exept=[]){

        foreach(config('services')['polimorfic'] as $k=>$rel){

            if(!in_array($rel,$exept)){

                $object->{$rel}()->detach();

            }

        }


        return config('services')['polimorfic'];
    }


}


