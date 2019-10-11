<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Category extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'categories';
    protected $fillable = ['ref','name','alias','description','status'];

    public function articles(){
        return $this->hasMany('App\Entities\Article');
    }

}
