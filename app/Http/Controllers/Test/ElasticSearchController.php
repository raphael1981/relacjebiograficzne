<?php
/**
 * Created by PhpStorm.
 * User: Robert
 * Date: 15.11.2017
 * Time: 11:32
 */

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories;
use App\Entities\Tag;
use App\Entities\Place;
use App\Entities\Record;

class ElasticSearchController
{



    public function getAllRecords(){
        return Record::all();
    }

    public function getAllTags(){
        return Tag::all();
    }

    public function getAllPlaces(){
        return Place::all();
    }
}