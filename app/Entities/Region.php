<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';
    protected $fillable = ['name'];

    public $timestamps = false;

//    public function customers(){
//        return $this->hasMany('App\Entities\Customer');
//    }

}
