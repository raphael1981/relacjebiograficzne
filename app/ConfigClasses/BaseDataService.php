<?php

namespace App\ConfigClasses;


use App\Entities\Region;

class BaseDataService{

    public function getRegions(){
        return Region::all();
    }

}